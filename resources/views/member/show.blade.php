@extends('member.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<div class="container mt-5">
        </a>
    {{-- ถ้าเป็นรายการออเดอร์ทั้งหมด --}}
    @if(isset($orders))

        <h2>ประวัติการสั่งซื้อของฉัน</h2>
        @if($orders->isEmpty())
            <div class="alert alert-info mt-3">คุณยังไม่มีคำสั่งซื้อ</div>
        @else
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
                        <th>รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_price, 2) }} บาท</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>
                                <a href="{{ route('member.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    {{-- ถ้าเป็นรายละเอียดออเดอร์ตัวเดียว --}}
    @if(isset($order))
        <h2>รายละเอียดคำสั่งซื้อ #{{ $order->id }}</h2>
        <p><strong>ผู้สั่งซื้อ:</strong> {{ $order->name }}</p>
        <p><strong>ที่อยู่:</strong> {{ $order->address }}</p>
        <p><strong>เบอร์โทร:</strong> {{ $order->phone }}</p>
        <p><strong>สถานะ:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>ยอดรวม:</strong> {{ number_format($order->total_price, 2) }} บาท</p>

        <h4 class="mt-4">รายการสินค้า</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th>จำนวน</th>
                    <th>ราคา/ชิ้น</th>
                    <th>รวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('member.orders') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> ย้อนกลับ
        </a>
    @endif

</div>
@endsection
