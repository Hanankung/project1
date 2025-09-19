@extends('layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        {{-- ฟอนต์ (ถ้าโหลดจาก layout แล้ว ตัดบรรทัดนี้ได้) --}}
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            :root {
                --brand: #2e7d32;
                /* เขียวแบรนด์: เหมือนหน้าสมาชิก */
                --brand-d: #1f5f24;
                --ink: #1f2937;
                --muted: #6b7280;
            }

            * {
                font-family: "Prompt", system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans Thai", Arial, sans-serif
            }

            /* === การ์ดสินค้า: เหมือนฝั่งสมาชิก === */
            .card {
                border-radius: 16px;
                border: 1px solid rgba(0, 0, 0, .05);
                overflow: hidden;
                transition: transform .2s ease, box-shadow .2s ease;
                box-shadow: 0 14px 34px rgba(0, 0, 0, .06);
                background: #fff;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 46px rgba(0, 0, 0, .1);
            }

            .card-img-top {
                height: 180px;
                object-fit: cover;
                cursor: pointer;
            }

            .card-body {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .card-title {
                font-weight: 700;
                color: var(--ink);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
            }

            /* คลิปเฉพาะชื่อสินค้า */
            .card-title .product-name {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* กัน badge ถูกบีบ/ตัด */
            .card-title .badge {
                flex: 0 0 auto;
                white-space: nowrap;
                overflow: visible;
                text-overflow: clip;
            }


            /* ชิปแสดงราคา */
            .card-body>p.mb-1 {
                background: linear-gradient(180deg, #fff, #f7fff7);
                border: 1px solid rgba(46, 125, 50, .12);
                border-radius: 10px;
                padding: 8px 10px;
                margin-bottom: 0;
                color: #334155;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .card-body>p.mb-1 strong {
                color: #0f172a;
            }

            /* ปุ่ม: เหมือนหน้าสมาชิก */
            .btn {
                border-radius: 12px;
                font-weight: 700;
                letter-spacing: .2px;
            }

            .btn-group-custom {
                display: flex;
                gap: 8px;
                align-items: center;
                margin: 6px 0 2px;
            }

            .btn-cart {
                background: #fff;
                color: var(--brand);
                width: 48px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(46, 125, 50, .22);
                box-shadow: 0 8px 20px rgba(46, 125, 50, .10);
            }

            .btn-cart:hover {
                background: #f4fbf5;
                color: var(--brand-d);
                border-color: rgba(46, 125, 50, .35);
            }

            .btn-buy {
                background: linear-gradient(180deg, var(--brand), #32903a);
                color: #fff;
                flex: 1;
                height: 40px;
                border: none;
                box-shadow: 0 12px 26px rgba(46, 125, 50, .22);
            }

            .btn-buy:hover {
                filter: brightness(.98);
                color: #fff;
            }

            .btn-detail {
                border: 1px solid rgba(46, 125, 50, .25);
                color: var(--brand);
                background: #fff;
            }

            .btn-detail:hover {
                background: var(--brand);
                color: #fff;
            }

            /* ป้าย “สินค้าหมด” ให้เหมือนสมาชิก */
            .card .badge.bg-secondary {
                background: linear-gradient(180deg, #ef4444, #dc2626) !important;
                color: #fff !important;
                border: 1px solid #b91c1c;
                border-radius: 999px;
                font-weight: 800;
                padding: .35rem .6rem;
                font-size: .9rem;
                letter-spacing: .2px;
                white-space: nowrap;
                box-shadow: 0 10px 22px rgba(239, 68, 68, .25);
            }

            @media (max-width:576px) {
                .row.g-4 {
                    --bs-gutter-x: 1rem;
                    --bs-gutter-y: 1rem;
                }

                .card-img-top {
                    height: 170px;
                }
            }
        </style>
    </head>

    @php
        $locale = app()->getLocale();
        $nameField = $locale === 'en' ? 'product_name_ENG' : ($locale === 'ms' ? 'product_name_MS' : 'product_name');
    @endphp

    <div class="container my-4">
        <div class="row row-cols-1 row-cols-md-6 g-4">
            @forelse($products as $product)
                @php
                    $qty = (int) ($product->quantity ?? 0);
                    $inStock = $qty > 0;
                @endphp

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('guest.products.show', $product->id) }}" class="d-block">
                            @if ($product->product_image)
                                <img src="{{ asset($product->product_image) }}" class="card-img-top"
                                    alt="{{ $product->$nameField ?? $product->product_name }}">
                            @else
                                <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="No image">
                            @endif
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <span class="product-name">{{ $product->$nameField ?? $product->product_name }}</span>
                                @if ((int) $product->quantity <= 0)
                                    <span class="badge bg-secondary">{{ __('messages.Sold out') }}</span>
                                @endif
                            </h5>


                            <p class="mb-1">
                                <strong>{{ __('messages.price') }}:</strong>
                                {{ number_format($product->price, 2) }} {{ __('messages.baht') }}
                            </p>

                            <div class="btn-group-custom mb-2">
                                @if (!$inStock)
                                    <button class="btn btn-secondary" style="width:48px;height:40px;" disabled>
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <button class="btn btn-secondary flex-1 w-100" disabled>
                                        <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                    </button>
                                @else
                                    @auth
                                        {{-- ล็อกอินแล้ว: ทำงานจริง --}}
                                        <form action="{{ route('cart.store') }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-cart"
                                                title="{{ __('messages.add_to_cart') }}">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('checkout.buy_now') }}" method="POST" class="flex-1 m-0 p-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-buy w-100">
                                                <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                            </button>
                                        </form>
                                    @else
                                        {{-- ยังไม่ล็อกอิน: ให้เด้งโมดอล --}}
                                        {{-- <button type="button" class="btn btn-cart" data-auth="required"
                                            title="{{ __('messages.add_to_cart_login_first') ?? 'กรุณาเข้าสู่ระบบก่อนเพิ่มลงตะกร้า' }}">
                                            <i class="bi bi-cart-plus"></i>
                                        </button> --}}
                                        <button type="button" class="btn btn-cart" data-auth="required"
                                            data-auth-title="{{ __('messages.auth_required_title_checkout') }}"
                                            data-auth-message="{{ __('messages.auth_required_msg_checkout') }}">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-buy w-100" data-auth="required"
                                            data-auth-title="{{ __('messages.auth_required_title_checkout') }}"
                                            data-auth-message="{{ __('messages.auth_required_msg_checkout') }}">
                                            <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                        </button>

                                    @endauth
                                @endif
                            </div>

                            <a href="{{ route('guest.products.show', $product->id) }}" class="btn btn-detail mt-auto">
                                {{ __('messages.description') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">{{ __('messages.warn') }}</div>
            @endforelse
        </div>
    </div>
@endsection
