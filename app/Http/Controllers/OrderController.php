<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

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
public function cancel($id)
{
    $order = \App\Models\Order::where('id', $id)
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->with('items') // << ต้องมีความสัมพันธ์ items
        ->firstOrFail();

    // แปลงสถานะดิบจาก DB (เก็บเป็นคำไทย)
    $raw = $order->status;

    $statusMap = [
        'รอดำเนินการ'       => 'pending',
        'อนุมัติแล้ว'       => 'approved',
        'ไม่อนุมัติ'        => 'rejected',
        'กำลังจัดส่งแล้ว'   => 'status_shipped',
        'จัดส่งสำเร็จ'       => 'status_delivered',
        'ยกเลิก'            => 'status_cancelled',
        // รองรับกรณีบางเรคคอร์ดอาจเก็บคีย์กลางอยู่แล้ว
        'pending'            => 'pending',
        'approved'           => 'approved',
        'rejected'           => 'rejected',
        'status_shipped'     => 'status_shipped',
        'status_delivered'   => 'status_delivered',
        'status_cancelled'   => 'status_cancelled',
    ];

    $statusKey = $statusMap[$raw] ?? $raw;

    // อนุญาตให้ยกเลิกได้เฉพาะตอน pending
    if ($statusKey !== 'pending') {
        return back()->with('error', __('messages.cannot_cancel'));
    }

    // เซ็ตสถานะใน DB ให้ "สอดคล้องกับที่คุณใช้จริง" (เก็บคำไทย)
    DB::transaction(function () use ($order) {
        // คืนสต็อก
        foreach ($order->items as $it) {
            $product = Post::where('id', $it->product_id)->lockForUpdate()->first();
            if ($product) $product->incrementStock($it->quantity);
        }

        $order->status = 'ยกเลิก';
        $order->save();
    });


    return redirect()
        ->route('member.orders.show', $order->id)
        ->with('success', __('messages.cancelled_success'));
    }
}
