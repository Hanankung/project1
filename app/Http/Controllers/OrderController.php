<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // แสดงรายละเอียดออเดอร์
    public function show($id)
    {
        // ดึงข้อมูล Order พร้อมสินค้า (order_items + product)
        $order = Order::with('items.product')->findOrFail($id);

        return view('member.show', compact('order'));
    }
}
