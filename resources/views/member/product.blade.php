@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        {{-- ฟอนต์อ่านสวย --}}
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            :root {
                --brand: #2e7d32;
                /* เขียวแบรนด์ */
                --brand-d: #1f5f24;
                /* เขียวเข้ม */
                --ink: #1f2937;
                --muted: #6b7280;
                --ring: rgba(46, 125, 50, .18);
            }

            * {
                font-family: "Prompt", system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans Thai", Arial, sans-serif
            }

            /* การ์ดสินค้า */
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
                margin-bottom: .25rem;
            }

            .card-title span {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* แถบราคาให้เด่นขึ้น */
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

            /* กลุ่มปุ่ม */
            .btn-group-custom {
                display: flex;
                gap: 8px;
                align-items: center;
                margin: 6px 0 2px;
            }

            .btn {
                border-radius: 12px;
                font-weight: 700;
                letter-spacing: .2px;
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
                box-shadow: 0 12px 26px rgba(46, 125, 50, .22);
                border: none;
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

            /* ป้ายสต็อก */
            .badge.bg-secondary {
                background: #e5e7eb !important;
                color: #374151 !important;
                border: 1px solid #d1d5db;
                border-radius: 999px;
            }

            /* ปุ่ม “ดูคำสั่งซื้อ” ด้านบนให้เข้าธีม */
            .btn-primary {
                --bs-btn-bg: var(--brand);
                --bs-btn-border-color: var(--brand);
                --bs-btn-hover-bg: #256c2a;
                --bs-btn-hover-border-color: #256c2a;
                --bs-btn-focus-shadow-rgb: 46, 125, 50;
                border-radius: 12px;
                font-weight: 800;
                box-shadow: 0 10px 24px rgba(46, 125, 50, .18);
            }

            /* grid spacing บนจอเล็กให้โปร่งขึ้นนิดนึง */
            @media (max-width: 576px) {
                .row.g-4 {
                    --bs-gutter-x: 1rem;
                    --bs-gutter-y: 1rem;
                }

                .card-img-top {
                    height: 170px;
                }
            }

            /* ==== SOLD OUT badge: แดง เด่น ชัด และไม่ถูกตัดคำ ==== */
            .card .badge.bg-secondary {
                background: linear-gradient(180deg, #ef4444, #dc2626) !important;
                /* แดงไล่เฉด */
                color: #fff !important;
                border: 1px solid #b91c1c;
                border-radius: 999px;
                font-weight: 800;
                padding: .35rem .6rem;
                font-size: .9rem;
                letter-spacing: .2px;
                white-space: nowrap;
                /* ห้ามตัดคำ */
                box-shadow: 0 10px 22px rgba(239, 68, 68, .25);
            }

            /* กันไม่ให้ badge ถูกบีบ/ทับโดยชื่อสินค้า */
            .card-title {
                gap: 8px;
            }

            .card-title .badge {
                flex: 0 0 auto;
            }
        </style>
    </head>
    @php
        $locale = app()->getLocale();
        $nameField = $locale === 'en' ? 'product_name_ENG' : ($locale === 'ms' ? 'product_name_MS' : 'product_name');
    @endphp

    {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: @json(__('messages.added_to_cart_title', [], app()->getLocale())),
                    text: @json(session('success')),
                    showCancelButton: true,
                    confirmButtonText: @json(__('messages.go_to_cart', [], app()->getLocale())),
                    cancelButtonText: @json(__('messages.continue_shopping', [], app()->getLocale())),
                    confirmButtonColor: '#2e7d32',
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = @json(route('member.cart'));
                    }
                });
            });
        </script>
    @endif

    <!-- ปุ่ม ดูคำสั่งซื้อ -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('member.orders') }}" class="btn btn-primary">
            <i class="bi bi-list-check"></i> {{ __('messages.View orders') }}
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-6 g-4">
        @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <a href="{{ route('member.product.show', $product->id) }}" class="d-block">
                        @if ($product->product_image)
                            <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top"
                                alt="{{ $product->$nameField ?? $product->product_name }}">
                        @else
                            <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="ไม่มีรูปภาพ">
                        @endif
                    </a>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title d-flex align-items-center justify-content-between">
                            <span>{{ $product->$nameField ?? $product->product_name }}</span>
                            @if ((int) $product->quantity <= 0)
                                <span class="badge bg-secondary">{{ __('messages.Sold out') }}</span>
                            @endif
                        </h5>

                        <p class="mb-1"><strong>{{ __('messages.price') }}:</strong> {{ $product->price }}
                            {{ __('messages.baht') }}</p>
                        <!-- ปุ่มตะกร้า + สั่งซื้อ -->
                        <div class="btn-group-custom mb-2">
                            @if ((int) $product->quantity <= 0)
                                <button class="btn btn-secondary" style="width:48px;height:38px;" disabled>
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                                <button class="btn btn-secondary flex-1 w-100" disabled>
                                    <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                </button>
                            @else
                                <form action="{{ route('cart.store') }}" method="POST" style="margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-cart">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>

                                <form action="{{ route('checkout.buy_now') }}" method="POST"
                                    style="flex:1; margin-left:8px;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-buy w-100">
                                        <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        <!-- ปุ่มรายละเอียด -->
                        <a href="{{ route('member.product.show', $product->id) }}" class="btn btn-detail mt-auto">
                            {{ __('messages.description') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">{{ __('messages.warn') }}</div>
        @endforelse

    </div>
@endsection
