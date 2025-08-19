@extends('member.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .cart-table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .quantity-input {
            width: 70px;
            text-align: center;
        }
        .btn-remove {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
        .btn-checkout {
            background-color: #28a745;
            color: #fff;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .btn-checkout:hover {
            background-color: #218838;
        }
    </style>
</head>

<div class="container mt-4">
    <h2 class="mb-4">ตะกร้าสินค้า</h2>

    @if(count($cartItems) > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle cart-table">
            <thead class="table-light">
                <tr>
                    <th>สินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวน</th>
                    <th>รวม</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ asset($item->product->product_image) }}" alt="{{ $item->product->product_name }}">
                        </td>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ number_format($item->product->price, 2) }} บาท</td>
                        <td>
                            <form action="{{ route('member.cart.update', $item->id) }}" method="POST" class="d-flex">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control quantity-input me-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ number_format($subtotal, 2) }} บาท</td>
                        <td>
                            <form action="{{ route('member.cart.delete', $item->id) }}" method="POST" onsubmit="return confirm('ต้องการลบสินค้านี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- สรุปราคารวม -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>ราคารวมทั้งหมด: <strong>{{ number_format($total, 2) }} บาท</strong></h4>
        <a href="#" class="btn btn-checkout">
            <i class="bi bi-credit-card"></i> ชำระเงิน
        </a>
    </div>
    @else
    <div class="alert alert-info text-center">
        ไม่มีสินค้าในตะกร้า
    </div>
    @endif
    
</div>
@endsection
