@extends('layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .card {
                border-radius: 10px;
                overflow: hidden;
                transition: transform 0.2s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            }

            .card-img-top {
                height: 180px;
                object-fit: cover;
            }

            .btn-group-custom {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .btn-cart {
                background-color: #ffc107;
                color: #fff;
                width: 48px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
            }

            .btn-cart:hover {
                background-color: #e0a800;
                color: #fff;
            }

            .btn-buy {
                background-color: #28a745;
                color: #fff;
                flex: 1;
                margin-left: 8px;
            }

            .btn-buy:hover {
                background-color: #218838;
                color: #fff;
            }

            .btn-detail {
                background-color: #0d6efd;
                color: #fff;
                width: 100%;
            }

            .btn-detail:hover {
                background-color: #0b5ed7;
                color: #fff;
            }

            .card-body {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        </style>
    </head>

    <div class="row row-cols-1 row-cols-md-6 g-4">
        @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if ($product->product_image)
                        <img src="{{ asset($product->product_image) }}" class="card-img-top"
                            alt="{{ $product->product_name }}">
                    @else
                        <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="ไม่มีรูปภาพ">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="mb-1"><strong>ราคา:</strong> {{ $product->price }} บาท</p>
                        <!-- ปุ่มตะกร้า + สั่งซื้อ -->
                        <div class="btn-group-custom mb-2">
                            <form action="{{ route('cart.store') }}" method="POST" style="margin:0; padding:0;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-cart">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                            <a href="#" class="btn btn-buy">
                                <i class="bi bi-bag-check"></i> สั่งซื้อ
                            </a>
                        </div>


                        <!-- ปุ่มรายละเอียด -->
                        <a href="{{ route('guest.products.show', $product->id) }}" class="btn btn-detail mt-auto">
                            รายละเอียด
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">ยังไม่มีสินค้าในระบบ</div>
        @endforelse
        
    </div>
@endsection
