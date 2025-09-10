<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Support\ShippingQuote;
use App\Models\Post;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use stdClass;

class CheckoutController extends Controller
{
    // แสดงหน้า checkout
    public function index(Request $request)
    {
        // 1) ถ้ามี session('checkout') => สร้างรายการชั่วคราวให้ view ใช้เหมือน cartItems
        $checkoutLines = session('checkout', []);
        if (!empty($checkoutLines)) {
            $qtyMap = [];
            foreach ($checkoutLines as $line) {
                $pid = (int) $line['product_id'];
                $qtyMap[$pid] = (int) $line['quantity'];
            }

            $products = Post::whereIn('id', array_keys($qtyMap))
                ->get()
                ->keyBy('id');

            $cartItems = collect();
            foreach ($qtyMap as $pid => $qty) {
                if (!$products->has($pid)) {
                    continue;
                }
                $row = new stdClass();
                $row->product_id = $pid;            // ✅ เก็บ product_id
                $row->product    = $products[$pid]; // ให้ blade เข้าถึงราคา/ชื่อได้เหมือนเดิม
                $row->quantity   = $qty;
                $cartItems->push($row);
            }
        } else {
            // 2) ไม่งั้นใช้ของในตะกร้าจริง
            $cartItems = Cart::with([
                'product:id,product_name,price,product_image,quantity,low_stock_threshold'
            ])->where('user_id', Auth::id())->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('member.cart')->with('error', __('messages.cart_empty'));
            }
        }
        $errors = [];
        foreach ($cartItems as $item) {
            if ((int)$item->product->quantity <= 0) {
                $errors[] = __('messages.product_out_of_stock_remove', [
                    'name' => $item->product->product_name
                ]);
            }
        }
        if (!empty($errors)) {
            return redirect()->route('member.cart')->with('error', implode("\n", $errors));
        }


        // คำนวณ subtotal / ค่าขนส่ง / grand total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->product->price * $item->quantity);
        }

        $country = strtoupper($request->input('country', 'TH'));
        $quote   = ShippingQuote::quote($cartItems, $country);
        $grand   = $subtotal + $quote['total_fee'];
        $rateMyr  = (float) config('currency.rates.THB_MYR', 0.13);
        $grandMyr = ($country === 'MY') ? round($grand * $rateMyr, 2) : null;

        return view('member.checkout', [
            'cartItems'  => $cartItems,
            'subtotal'   => $subtotal,
            'quote'      => $quote,
            'grandTotal' => $grand,
            'country'    => $country,
            'rateMyr'    => $rateMyr,     // ✅ ส่งเรทไปด้วย
            'grandMyr'   => $grandMyr,    // ✅ ส่งยอด MYR (หรือ null ถ้าไม่ใช่ MY)
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
            return redirect()->route('member.cart')->with('error', __('messages.cart_empty'));
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

    // คำนวณค่าส่งแบบ AJAX เมื่อเปลี่ยนประเทศ
    public function quote(Request $request)
    {
        // 1) พยายามอ่านจาก session('checkout') ก่อน (กรณี Buy Now/From Cart)
        $lines = session('checkout', []);
        $items = collect();

        if (!empty($lines)) {
            // build items จาก session เหมือนใน index()
            $qtyMap = [];
            foreach ($lines as $line) {
                $qtyMap[(int) $line['product_id']] = (int) $line['quantity'];
            }

            $products = Post::whereIn('id', array_keys($qtyMap))
                ->get()
                ->keyBy('id');

            foreach ($qtyMap as $pid => $qty) {
                if (!$products->has($pid)) continue;
                $row = new \stdClass();
                $row->product_id = $pid;
                $row->product    = $products[$pid];
                $row->quantity   = $qty;
                $items->push($row);
            }
        }

        // 2) ถ้า session ไม่มีของ ให้ดึงจากตะกร้าจริง
        if ($items->isEmpty()) {
            $items = Cart::with([
                'product:id,product_name,price,product_image,quantity,low_stock_threshold'
            ])->where('user_id', Auth::id())->get();
        }

        // 3) ถ้ายังว่างอยู่จริง ๆ ให้แจ้ง error
        if ($items->isEmpty()) {
            return response()->json(['error' => __('messages.quote_cart_empty')], 400);
        }

        // 4) คำนวณ subtotal / ค่าขนส่ง / grand
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $country = strtoupper($request->input('country', 'TH'));
        $quote   = ShippingQuote::quote($items, $country);
        $grand   = $subtotal + $quote['total_fee'];

        // 5) แปลงเป็นริงกิต (แค่แสดงให้เห็น)
        $rateMyr  = (float) config('currency.rates.THB_MYR', 0.13);
        $grandMyr = ($country === 'MY') ? number_format($grand * $rateMyr, 2) : null;

        return response()->json([
            'subtotal'  => number_format($subtotal, 2),
            'shipping'  => number_format($quote['shipping'], 2),
            'box'       => number_format($quote['box'], 2),
            'handling'  => number_format($quote['handling'], 2),
            'grand'     => number_format($grand, 2),

            // ข้อมูลสำหรับแสดง MYR
            'grand_myr' => $grandMyr,
            'rate_myr'  => number_format($rateMyr, 4),
            'country'   => $country,
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
            $qtyMap = [];
            foreach ($lines as $line) {
                $qtyMap[(int)$line['product_id']] = (int)$line['quantity'];
            }

            $products = Post::whereIn('id', array_keys($qtyMap))->get()->keyBy('id');

            foreach ($qtyMap as $pid => $qty) {
                if (!$products->has($pid)) continue;
                $row = new stdClass();
                $row->product_id = $pid;           // ✅ เก็บ product_id
                $row->product    = $products[$pid];
                $row->quantity   = $qty;
                $items->push($row);
            }
        } else {
            // fallback: ดึงจากตะกร้าจริง
            $items = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($items->isEmpty()) {
                return redirect()->route('member.cart')->with('error', __('messages.cart_empty'));
            }
        }

        // คำนวณเงิน
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $quote      = ShippingQuote::quote($items, strtoupper($request->country));
        $grandTotal = $subtotal + $quote['total_fee'];

        // ทำงานแบบ transaction + lock row เพื่อตัดสต็อก
        $lowStockProducts = [];
        $orderId = null;

        try {
            DB::transaction(function () use ($items, $request, $subtotal, $quote, $grandTotal, &$lowStockProducts, &$orderId) {

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

                $orderId = $order->id;

                foreach ($items as $item) {
                    // ✅ หา productId ที่เชื่อถือได้ (ทั้งเคส session และ cart)
                    $productId = $item->product_id ?? optional($item->product)->getKey();
                    if (!$productId) {
                        throw new \RuntimeException(__('messages.product_id_missing_in_cart'));
                    }

                    /** @var \App\Models\Post $product */
                    $product = Post::where('id', $productId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!$product->hasStock($item->quantity)) {
                        throw new \RuntimeException(__('messages.insufficient_stock', [
                            'name' => $product->product_name,
                            'left' => $product->quantity
                        ]));
                    }

                    // ตัดสต็อก
                    $product->decrementStock($item->quantity);

                    // บันทึกรายการสินค้าในออเดอร์
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productId,     // ✅ ใช้ productId ที่หาไว้
                        'quantity'   => $item->quantity,
                        'price'      => $product->price,
                    ]);

                    // ถ้าสต็อกต่ำกว่าเกณฑ์ เก็บไว้แจ้งเตือน (อย่าใช้ fresh([...]) กับชื่อคอลัมน์)
                    if ($product->isLow()) {
                        $lowStockProducts[] = (object)[
                            'id' => $product->id,
                            'product_name' => $product->product_name,
                            'quantity' => $product->quantity,
                            'low_stock_threshold' => $product->low_stock_threshold,
                        ];
                    }
                }

                // Flash คำเตือนไปหน้าแอดมิน
                if (!empty($lowStockProducts)) {
                    session()->flash('low_stock_warnings', collect($lowStockProducts)->map(function ($p) {
                        return [
                            'id'        => $p->id,
                            'name'      => $p->product_name,
                            'qty'       => $p->quantity,
                            'threshold' => $p->low_stock_threshold,
                        ];
                    })->values()->all());
                }
            });
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }

        // เคลียร์สถานะ checkout
        session()->forget(['checkout', 'checkout_source']);
        if ($source === 'cart') {
            Cart::where('user_id', Auth::id())->delete();
        }

        return redirect()
            ->route('member.orders.show', $orderId ?? Order::latest('id')->value('id'))
            ->with('success', __('messages.order_success'));
    }
}
