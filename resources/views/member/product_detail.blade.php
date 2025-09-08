@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .product-container {
                max-width: 900px;
                margin: 0 auto;
                background: #fff;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            /* ปุ่มย้อนกลับ */
            .back-btn {
                position: absolute;
                top: 15px;
                left: 15px;
                font-size: 24px;
                color: #333;
                text-decoration: none;
            }

            .back-btn:hover {
                color: #0d6efd;
            }

            .product-image {
                max-width: 300px;
                max-height: 300px;
                object-fit: cover;
                border-radius: 8px;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }

            .product-title {
                font-size: 28px;
                font-weight: bold;
                margin-bottom: 15px;
                color: #333;
            }

            .product-details p {
                font-size: 16px;
                margin-bottom: 8px;
            }

            .btn-group-custom {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }

            .btn-cart {
                background-color: #ffc107;
                color: #fff;
                flex: 1;
            }

            .btn-cart:hover {
                background-color: #e0a800;
                color: #fff;
            }

            .btn-buy {
                background-color: #28a745;
                color: #fff;
                flex: 1;
            }

            .btn-buy:hover {
                background-color: #218838;
                color: #fff;
            }
        </style>
    </head>
    @php
        $locale = App::getLocale();
        $nameField = $locale === 'en' ? 'product_name_ENG' : ($locale === 'ms' ? 'product_name_MS' : 'product_name');
        $descField = $locale === 'en' ? 'description_ENG' : ($locale === 'ms' ? 'description_MS' : 'description');
        $materialField = $locale === 'en' ? 'material_ENG' : ($locale === 'ms' ? 'material_MS' : 'material');
    @endphp
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-5">
        <div class="product-container">
            <!-- ปุ่มย้อนกลับ -->
            <a href="{{ url()->previous() }}" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i>
            </a>

            <div class="row align-items-center">
                <!-- รูปสินค้า (ซ้าย) -->
                <div class="col-md-5 text-center">
                    @if ($product->product_image)
                        <img src="{{ asset($product->product_image) }}" class="product-image"
                            alt="{{ $product->product_name }}">
                    @else
                        <img src="{{ asset('images/default.png') }}" class="product-image" alt="ไม่มีรูปภาพ">
                    @endif
                </div>

                <!-- ข้อมูลสินค้า (ขวา) -->
                <div class="col-md-7 product-details">
                    <h1 class="product-title">{{ $product->$nameField ?? '-' }}</h1>
                    <p><strong>{{ __('messages.price') }}:</strong> {{ number_format($product->price, 2) }}
                        {{ __('messages.baht') }}</p>
                    <p><strong>{{ __('messages.description') }}:</strong> {{ $product->$descField ?? '-' }}</p>
                    <p><strong>{{ __('messages.quantity') }}:</strong> {{ $product->quantity ?? '-' }}</p>
                    <p><strong>{{ __('messages.material') }}:</strong> {{ $product->$materialField ?? '-' }}</p>
                    <p><strong>{{ __('messages.size') }}:</strong> {{ $product->size ?? '-' }}</p>

                    <!-- ปุ่มตะกร้า + สั่งซื้อ -->
                    <div class="btn-group-custom">
                        @if ((int) $product->quantity <= 0)
                            <div class="alert alert-danger w-100 mb-0">
                                {{ __('messages.status_product') }}
                            </div>

                            <button class="btn btn-secondary" disabled style="flex:1;">
                                <i class="bi bi-cart-plus"></i> {{ __('messages.add_to_cart') }}
                            </button>

                            <button class="btn btn-secondary" disabled style="flex:1;">
                                <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                            </button>
                        @else
                            <form action="{{ route('cart.store') }}" method="POST" style="margin:0; padding:0;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                {{-- ถ้าอยากให้เลือกจำนวนเอง ค่อยเพิ่ม input number ทีหลังได้ --}}
                                <button type="submit" class="btn btn-cart">
                                    <i class="bi bi-cart-plus"></i> {{ __('messages.add_to_cart') }}
                                </button>
                            </form>

                            <form action="{{ route('checkout.buy_now') }}" method="POST" style="flex:1;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-buy w-100">
                                    <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
