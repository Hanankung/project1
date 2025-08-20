@extends('admin.layout')

@section('content')
<head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container mt-4">
    <h2>รายการคำสั่งซื้อทั้งหมด</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>ผู้สั่งซื้อ</th>
                <th>ยอดรวม</th>
                <th>สถานะ</th>
                <th>วันที่สั่งซื้อ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ number_format($order->total_price, 2) }} ฿</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">ดูรายละเอียด</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
