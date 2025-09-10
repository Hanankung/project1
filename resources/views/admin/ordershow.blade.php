@extends('admin.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<div class="container mt-4">
    <h2>รายละเอียดคำสั่งซื้อ #{{ $order->id }}</h2>

    <p><strong>ชื่อผู้สั่งซื้อ:</strong> {{ $order->name }}</p>
    <p><strong>ยอดรวม:</strong> {{ number_format($order->total_price, 2) }} ฿</p>
    <p><strong>สถานะปัจจุบัน:</strong> {{ $order->status }}</p>

    {{-- ✅ บล็อกสลิปการชำระเงิน (วางไว้ตรงนี้) --}}
    @if(!empty($order->payment_slip_path))
        <div class="mb-3">
            <label class="form-label d-block"><strong>สลิปการชำระเงิน:</strong></label>
            <a href="{{ asset('storage/'.$order->payment_slip_path) }}"
               target="_blank"
               class="btn btn-sm btn-outline-primary">
               <i class="bi bi-box-arrow-up-right"></i> เปิดดูสลิป
            </a>

            @php
                $ext = strtolower(pathinfo($order->payment_slip_path, PATHINFO_EXTENSION));
            @endphp
            @if(in_array($ext, ['jpg','jpeg','png']))
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$order->payment_slip_path) }}"
                         alt="Payment slip"
                         class="img-thumbnail"
                         style="max-width: 280px;">
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-warning">ยังไม่มีสลิปแนบมาพร้อมคำสั่งซื้อนี้</div>
    @endif
    {{-- ✅ จบบล็อกสลิป --}}

    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
        @csrf
        <div class="form-group mt-3">
            <label for="status">อัปเดตสถานะ:</label>
            <select name="status" id="status" class="form-control">
                <option value="รออนุมัติ"   {{ $order->status == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ</option>
                <option value="อนุมัติแล้ว" {{ $order->status == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="กำลังจัดส่ง" {{ $order->status == 'กำลังจัดส่ง' ? 'selected' : '' }}>กำลังจัดส่ง</option>
                <option value="จัดส่งแล้ว"   {{ $order->status == 'จัดส่งแล้ว' ? 'selected' : '' }}>จัดส่งแล้ว</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mt-2">บันทึก</button>
    </form>

    <h3 class="mt-4">สินค้าในคำสั่งซื้อ</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->product_name }} x {{ $item->quantity }} = {{ number_format($item->price, 2) }} ฿</li>
        @endforeach
    </ul>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
            </a>
</div>

@endsection
