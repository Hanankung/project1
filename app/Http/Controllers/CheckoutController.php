<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Support\ShippingQuote;

class CheckoutController extends Controller
{
    // แสดงหน้า checkout
    public function index(Request $request)
    {
        $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('member.cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');
    }

    // คำนวณ subtotal
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item->product->price * $item->quantity;
    }
    // ประเทศ (ถ้าในฟอร์มยังไม่มี select country ก็ตั้ง default = TH)
    $country = strtoupper($request->input('country', 'TH'));

    // ใช้ ShippingQuote ช่วยคำนวณค่าส่ง/กล่อง/handling
    $quote = \App\Support\ShippingQuote::quote($cartItems, $country);

    // รวมทั้งหมด
    $grandTotal = $subtotal + $quote['total_fee'];

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item->product->price * $item->quantity;
    }

    // ส่งตัวแปรทั้งหมดไปที่ blade
    return view('member.checkout', [
        'cartItems'  => $cartItems,
        'subtotal'   => $subtotal,
        'quote'      => $quote,
        'grandTotal' => $grandTotal,
        'country'    => $country,
    ]);
    
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

    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('member.cart')->with('error', 'ไม่มีสินค้าในตะกร้า');
    }
    // คำนวณ subtotal
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item->product->price * $item->quantity;
    }
     // ใช้ helper ShippingQuote ที่เราสร้างไว้
    $quote = ShippingQuote::quote($cartItems, strtoupper($request->country));

     // คำนวณยอดรวมสุทธิ
    $grandTotal = $subtotal + $quote['total_fee'];

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item->product->price * $item->quantity;
    }

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
        'currency'     => 'THB', // ค่าเริ่มต้น
        'total_price'  => $grandTotal, // ยอดสุทธิ
        'status'       => 'รอดำเนินการ',
    ]);

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $item->product_id,
            'quantity'   => $item->quantity,
            'price'      => $item->product->price,
        ]);
    }

    // ลบตะกร้าออก
    \App\Models\Cart::where('user_id', Auth::id())->delete();

    return redirect()->route('member.orders.show', $order->id)
                     ->with('success', 'สั่งซื้อสำเร็จแล้ว!');
}
}