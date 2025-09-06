<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Support\ShippingQuote;
use App\Models\Post;  
use App\Models\Cart;   
use stdClass;

class CheckoutController extends Controller
{
    // แสดงหน้า checkout
    public function index(Request $request)
    {
         // 1) ถ้ามี session('checkout') => สร้างรายการชั่วคราวให้ view ใช้เหมือน cartItems
        $checkoutLines = session('checkout', []);
        if (!empty($checkoutLines)) {
            $ids = array_column($checkoutLines, 'product_id');
            $qtyMap = [];
            foreach ($checkoutLines as $line) {
                $qtyMap[$line['product_id']] = (int) $line['quantity'];
            }

            $products = Post::whereIn('id', $ids)->get()->keyBy('id');

            $cartItems = collect();
            foreach ($qtyMap as $pid => $qty) {
                if (!$products->has($pid)) continue;
                $row = new stdClass();
                $row->product  = $products[$pid]; // ให้ blade เข้าถึง $item->product->price ฯลฯ ได้เหมือนเดิม
                $row->quantity = $qty;
                $cartItems->push($row);
            }
        } else {
            // 2) ไม่งั้นใช้ของในตะกร้าจริง
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('member.cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');
            }
        }

        // คำนวณ subtotal / ค่าขนส่ง / grand total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->product->price * $item->quantity);
        }

        $country = strtoupper($request->input('country', 'TH'));
        $quote   = ShippingQuote::quote($cartItems, $country);
        $grand   = $subtotal + $quote['total_fee'];

        return view('member.checkout', [
            'cartItems'  => $cartItems,
            'subtotal'   => $subtotal,
            'quote'      => $quote,
            'grandTotal' => $grand,
            'country'    => $country,
        ]);
    }

    // === NEW: Buy Now จากหน้าสินค้า (ไม่ผ่านตะกร้า)
    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        // เก็บเฉพาะ {product_id, quantity} ลง session
        session([
            'checkout'        => [[
                'product_id' => (int)$data['product_id'],
                'quantity'   => (int)$data['quantity'],
            ]],
            'checkout_source' => 'buy_now',
        ]);

        return redirect()->route('member.checkout');
    }

    // === NEW: เตรียม checkout จากตะกร้าทั้งหมด
    public function fromCart(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('member.cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');
        }

        $lines = [];
        foreach ($cartItems as $ci) {
            $lines[] = [
                'product_id' => $ci->product_id,
                'quantity'   => $ci->quantity,
            ];
        }

        session([
            'checkout'        => $lines,
            'checkout_source' => 'cart',
        ]);

        return redirect()->route('member.checkout');
    }

    public function quote(Request $request)
{
    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['error' => 'ตะกร้าว่าง'], 400);
    }

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item->product->price * $item->quantity;
    }

    $country = strtoupper($request->input('country', 'TH'));
    $quote   = \App\Support\ShippingQuote::quote($cartItems, $country);
    $grand   = $subtotal + $quote['total_fee'];

    return response()->json([
        'subtotal' => number_format($subtotal, 2),
        'shipping' => number_format($quote['shipping'], 2),
        'box'      => number_format($quote['box'], 2),
        'handling' => number_format($quote['handling'], 2),
        'grand'    => number_format($grand, 2),
    ]);
}


    // บันทึกคำสั่งซื้อ
    public function store(Request $request)
    {
        
         $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:20',
            'country' => 'required|string|size:2',
        ]);

        $source = session('checkout_source');
        $lines  = session('checkout', []);

        // เตรียม items สำหรับคำนวณ/บันทึก
        $items = collect();

        if (!empty($lines)) {
            // กรณี Buy Now หรือ From Cart ที่คัดลอกมาไว้ใน session แล้ว
            $ids = array_column($lines, 'product_id');
            $qtyMap = [];
            foreach ($lines as $line) {
                $qtyMap[$line['product_id']] = (int)$line['quantity'];
            }

            $products = Post::whereIn('id', $ids)->get()->keyBy('id');

            foreach ($qtyMap as $pid => $qty) {
                if (!$products->has($pid)) continue;
                $row = new stdClass();
                $row->product  = $products[$pid];
                $row->quantity = $qty;
                $items->push($row);
            }
        } else {
            // fallback: ดึงจากตะกร้าจริง (เหมือนโค้ดเดิมของคุณ)
            $items = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($items->isEmpty()) {
                return redirect()->route('member.cart')->with('error', 'ไม่มีสินค้าในตะกร้า');
            }
        }

        // คำนวณเงิน
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $quote      = ShippingQuote::quote($items, strtoupper($request->country));
        $grandTotal = $subtotal + $quote['total_fee'];

        // สร้าง Order
        $order = Order::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'address'      => $request->address,
            'phone'        => $request->phone,
            'country'      => strtoupper($request->country),
            'subtotal'     => $subtotal,
            'shipping_fee' => $quote['shipping'],
            'box_fee'      => $quote['box'],
            'handling_fee' => $quote['handling'],
            'currency'     => 'THB',
            'total_price'  => $grandTotal,
            'status'       => 'รอดำเนินการ',
        ]);

        // บันทึก Order Items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product->id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // เคลียร์สถานะ checkout
        session()->forget(['checkout', 'checkout_source']);

        // ถ้ามาจากตะกร้า ให้ล้างตะกร้าจริงด้วย
        if ($source === 'cart') {
            Cart::where('user_id', Auth::id())->delete();
        }

        return redirect()->route('member.orders.show', $order->id)
                         ->with('success', 'สั่งซื้อสำเร็จแล้ว!');
    }
}