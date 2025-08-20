<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderAdminController extends Controller
{
    // แสดงรายการออเดอร์ทั้งหมด
    public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.order', compact('orders'));
    }

    // แสดงรายละเอียดออเดอร์
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.ordershow', compact('order'));
    }

    // อัพเดทสถานะ
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // ส่งกลับไปหน้ารายการคำสั่งซื้อ พร้อมข้อความ success
    return redirect()->route('admin.orders.index')
                     ->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
