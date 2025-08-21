<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   // รายการออเดอร์
public function index()
{
    $orders = Order::where('user_id', Auth::id())
                   ->with('items.product')
                   ->latest()
                   ->get();

    return view('member.show', compact('orders'));
}

// รายละเอียดออเดอร์
public function show($id)
{
    $order = Order::with('items.product')->findOrFail($id);
    return view('member.show', compact('order'));
}
}
