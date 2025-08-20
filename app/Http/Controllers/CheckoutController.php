<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // แสดงหน้า checkout
    public function index()
    {
        $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('member.cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');
    }

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item->product->price * $item->quantity;
    }

    return view('member.checkout', compact('cartItems', 'total'));
    }

    // บันทึกคำสั่งซื้อ
    public function store(Request $request)
    {
        $request->validate([
        'name'    => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'phone'   => 'required|string|max:20',
    ]);

    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('member.cart')->with('error', 'ไม่มีสินค้าในตะกร้า');
    }

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item->product->price * $item->quantity;
    }

    $order = Order::create([
        'user_id'    => Auth::id(),
        'name'       => $request->name,
        'address'    => $request->address,
        'phone'      => $request->phone,
        'total_price'=> $total,
        'status'     => 'รอดำเนินการ',
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