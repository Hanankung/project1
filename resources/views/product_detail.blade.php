@extends('layout')

@section('content')

    
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        {{-- ฟอนต์อ่านสวยสำหรับภาษาไทย/EN/MS --}}
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --brand: #6b4e2e;
                /* โทนน้ำตาลพรีเมี่ยม */
                --brand-2: #8b6a46;
                --ink: #2b2b2b;
                --muted: #6c757d;
                --gold-1: #d4af37;
                --gold-2: #f6e27a;
                --card-bg: #ffffff;
                --ring: rgba(212, 175, 55, .35);
            }

            * {
                font-family: "Prompt", system-ui, -apple-system, "Segoe UI", Roboto, Arial, "Noto Sans Thai", sans-serif
            }

            .page-wrap {
                background: linear-gradient(180deg, #faf7f2 0%, #fff 60%);
                padding: 28px 0 56px
            }

            .product-card {
                max-width: 1100px;
                margin: 0 auto;
                background: var(--card-bg);
                border-radius: 18px;
                padding: 28px;
                box-shadow: 0 16px 40px rgba(0, 0, 0, .08);
                border: 1px solid rgba(139, 106, 70, .08);
                position: relative;
                overflow: hidden;
            }

            /* เส้นบนหัวการ์ด */
            .product-card:before {
                content: "";
                position: absolute;
                inset: 0 0 auto 0;
                height: 6px;
                background: linear-gradient(90deg, var(--gold-1), var(--gold-2), var(--gold-1));
                opacity: .55;
            }

            .crumbs {
                color: var(--muted);
                font-size: .92rem
            }

            .crumbs a {
                color: var(--muted);
                text-decoration: none
            }

            .crumbs a:hover {
                color: var(--brand)
            }

            .back-btn {
                position: absolute;
                top: 14px;
                left: 14px;
                z-index: 3;
                width: 40px;
                height: 40px;
                display: grid;
                place-items: center;
                border-radius: 999px;
                background: #fff;
                border: 1px solid #eee;
                color: #333;
                box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
                transition: transform .2s ease, box-shadow .2s ease;
            }

            .back-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, .10)
            }

            .gallery {
                background: #fff;
                border-radius: 14px;
                padding: 16px;
                border: 1px solid #eee;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
                position: relative;
            }

            .main-img {
                width: 100%;
                aspect-ratio: 1 / 1;
                object-fit: cover;
                border-radius: 12px;
                transition: transform .35s ease;
            }

            .main-img:hover {
                transform: scale(1.015)
            }

            .stock-badge {
                position: absolute;
                top: 18px;
                left: 18px;
                padding: 6px 12px;
                border-radius: 999px;
                font-weight: 600;
                font-size: .9rem;
                color: #2b2b2b;
                background: #fff;
                border: 1px solid #eee;
                box-shadow: 0 6px 16px rgba(0, 0, 0, .08)
            }

            .stock-badge.out {
                background: #f8d7da;
                color: #842029;
                border-color: #f5c2c7
            }

            .title {
                font-size: 1.9rem;
                font-weight: 700;
                color: var(--ink);
                letter-spacing: .2px
            }

            .price {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                margin-top: 6px;
                font-size: 1.55rem;
                font-weight: 700;
                color: #1e1e1e;
            }

            .price-badge {
                background: linear-gradient(120deg, var(--gold-1), var(--gold-2));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                font-weight: 800;
                letter-spacing: .3px;
            }

            .meta {
                color: var(--muted);
                font-size: .95rem
            }

            .meta strong {
                color: #475467
            }

            .divider {
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--ring), transparent);
                margin: 14px 0 10px
            }

            .section-title {
                font-weight: 700;
                color: var(--brand);
                font-size: 1.05rem;
                margin: 12px 0 6px
            }

            .ul-check {
                padding-left: 0;
                list-style: none;
                margin: 0
            }

            .ul-check li {
                padding-left: 28px;
                position: relative;
                margin: 6px 0;
                color: #2f2f2f
            }

            .ul-check li:before {
                content: "\F26E";
                font-family: "bootstrap-icons";
                position: absolute;
                left: 0;
                top: 1px;
                width: 20px;
                height: 20px;
                display: grid;
                place-items: center;
                border-radius: 999px;
                background: rgba(40, 167, 69, .12);
                color: #218838;
                font-size: .9rem;
            }

            .ul-dot {
                padding-left: 18px
            }

            .trust {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                margin-top: 14px
            }

            .trust .item {
                border: 1px dashed rgba(139, 106, 70, .35);
                border-radius: 12px;
                padding: 10px 12px;
                background: linear-gradient(180deg, #fff, #fff9ef);
                display: flex;
                align-items: start;
                gap: 10px;
                font-size: .92rem;
                color: #4a4a4a
            }

            .trust i {
                font-size: 1.1rem;
                color: var(--brand)
            }

            .btns {
                display: flex;
                gap: 12px;
                margin-top: 18px
            }

            .btn-cart {
                flex: 1;
                border: none;
                font-weight: 700;
                letter-spacing: .2px;
                padding: 12px 14px;
                background: linear-gradient(180deg, #ffd75b, #ffc107);
                color: #3a2f06;
                border-radius: 12px;
                box-shadow: 0 12px 24px rgba(255, 193, 7, .25);
            }

            .btn-cart:hover {
                filter: brightness(.98)
            }

            .btn-buy {
                flex: 1;
                border: none;
                font-weight: 800;
                padding: 12px 14px;
                border-radius: 12px;
                background: linear-gradient(180deg, #34d399, #28a745);
                color: white;
                box-shadow: 0 12px 24px rgba(40, 167, 69, .25);
            }

            .btn-buy:hover {
                filter: brightness(.98)
            }

            .btn-disabled {
                opacity: .65;
                cursor: not-allowed
            }

            .share {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-top: 10px;
                color: var(--muted)
            }

            .share a {
                width: 36px;
                height: 36px;
                border-radius: 999px;
                display: grid;
                place-items: center;
                border: 1px solid #e9ecef;
                color: #495057;
                background: #fff
            }

            .share a:hover {
                transform: translateY(-1px)
            }

            /* Sticky CTA บนมือถือ */
            .sticky-cta {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 50;
                background: rgba(255, 255, 255, .9);
                border-top: 1px solid #eee;
                backdrop-filter: blur(6px);
                padding: 10px;
                display: none;
            }

            @media (max-width: 576px) {
                .sticky-cta {
                    display: block
                }

                .btns {
                    display: none
                }

                /* ซ่อนปุ่มชุดใหญ่ เมื่อมี sticky */
            }
        </style>
    </head>

    @php
        $locale = app()->getLocale();
        $nameField = $locale === 'en' ? 'product_name_ENG' : ($locale === 'ms' ? 'product_name_MS' : 'product_name');
        $descField = $locale === 'en' ? 'description_ENG' : ($locale === 'ms' ? 'description_MS' : 'description');
        $materialField = $locale === 'en' ? 'material_ENG' : ($locale === 'ms' ? 'material_MS' : 'material');
    @endphp

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="page-wrap">
        <div class="container">
            <div class="product-card">
                <a href="{{ route('guest.products') }}" class="back-btn">
                    <i class="bi bi-arrow-left-circle"></i>
                </a>

                <div class="mb-2 crumbs">
                    <i class="bi bi-bag"></i>
                    <a href="#">{{ __('messages.product') ?? 'สินค้า' }}</a>
                    <span class="mx-1">/</span>
                    <span class="text-dark">{{ $product->$nameField ?? '-' }}</span>
                </div>

                <div class="row g-4 align-items-start">
                    {{-- Gallery / รูปสินค้า --}}
                    <div class="col-md-5">
                        <div class="gallery">
                            @if ((int) ($product->quantity ?? 0) > 0)
                                <span class="stock-badge"><i class="bi bi-box-seam me-1"></i>{{ __('messages.quantity') }}:
                                    {{ (int) $product->quantity }}</span>
                            @else
                                <span class="stock-badge out"><i
                                        class="bi bi-exclamation-triangle me-1"></i>{{ __('messages.status_product') ?? 'หมดชั่วคราว' }}</span>
                            @endif

                            @if ($product->product_image)
                                <img src="{{ asset($product->product_image) }}" class="main-img"
                                    alt="{{ $product->product_name }}">
                            @else
                                <img src="{{ asset('images/default.png') }}" class="main-img" alt="No Image">
                            @endif
                        </div>

                        {{-- Trust mini badges --}}
                        <div class="trust mt-3">
                            <div class="item"><i class="bi bi-shield-check"></i>
                                <div>
                                    <strong>{{ __('messages.trust.quality') }}</strong><br>{{ __('messages.trust.quality_desc') }}
                                </div>
                            </div>
                            <div class="item"><i class="bi bi-truck"></i>
                                <div>
                                    <strong>{{ __('messages.trust.ready') }}</strong><br>{{ __('messages.trust.ready_desc') }}
                                </div>
                            </div>
                            <div class="item"><i class="bi bi-telephone"></i>
                                <div>
                                    <strong>{{ __('messages.trust.support') }}</strong><br>{{ __('messages.trust.support_desc') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Details / รายละเอียด --}}
                    <div class="col-md-7">
                        <div class="title">{{ $product->$nameField ?? '-' }}</div>

                        <div class="price">
                            <span class="price-badge">{{ number_format($product->price, 2) }}
                                {{ __('messages.baht') }}</span>
                        </div>

                        <div class="meta mt-2">
                            <div><strong>{{ __('messages.material') }}:</strong> {{ $product->$materialField ?? '-' }}
                            </div>
                            <div><strong>{{ __('messages.size') }}:</strong> {{ $product->size ?? '-' }}</div>
                        </div>

                        <div class="divider"></div>

                        {{-- ======= แยกหัวข้อ รายละเอียด / คุณสมบัติ / การดูแลรักษา ======= --}}
                        @php
                            $raw = (string) ($product->$descField ?? '');
                            $labelMap = [
                                'th' => [
                                    'desc' => ['รายละเอียด'],
                                    'features' => ['คุณสมบัติ'],
                                    'care' => ['การดูแลรักษา'],
                                ],
                                'en' => [
                                    'desc' => ['Details', 'Description'],
                                    'features' => ['Features'],
                                    'care' => ['Care', 'Care Instructions', 'How to care'],
                                ],
                                'ms' => [
                                    'desc' => ['Perincian', 'Keterangan'],
                                    'features' => ['Ciri-ciri', 'Ciri'],
                                    'care' => ['Penjagaan', 'Cara penjagaan'],
                                ],
                            ];
                            $display = [
                                'th' => ['desc' => 'รายละเอียด', 'features' => 'คุณสมบัติ', 'care' => 'การดูแลรักษา'],
                                'en' => ['desc' => 'Details', 'features' => 'Features', 'care' => 'Care'],
                                'ms' => ['desc' => 'Perincian', 'features' => 'Ciri-ciri', 'care' => 'Penjagaan'],
                            ];
                            $labels = $labelMap[$locale] ?? $labelMap['en'];
                            $titles = $display[$locale] ?? $display['en'];

                            $allNames = [];
                            foreach ($labels as $arr) {
                                $allNames = array_merge($allNames, $arr);
                            }
                            $pregQuote = fn($v) => preg_quote($v, '/');
                            $joinAlt = fn($arr) => implode('|', array_map($pregQuote, $arr));
                            $allAlt = $joinAlt($allNames);

                            $extract = function (string $text, array $names) use ($allAlt, $joinAlt) {
                                $thisAlt = $joinAlt($names);
                                $pattern = "/(?:{$thisAlt})\\s*:\\s*(.*?)(?=(?:{$allAlt})\\s*:|$)/isu";
                                return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
                            };

                            $sec = [
                                'desc' => $extract($raw, $labels['desc']),
                                'features' => $extract($raw, $labels['features']),
                                'care' => $extract($raw, $labels['care']),
                            ];

                            $splitLines = function (?string $s) {
                                if (!$s) {
                                    return [];
                                }
                                $parts = preg_split('/\r\n|\r|\n|[•·]|[–—-]/u', $s);
                                return array_values(array_filter(array_map('trim', $parts), fn($t) => $t !== ''));
                            };
                            $features = $splitLines($sec['features']);
                            $care = $splitLines($sec['care']);
                            $noSections = empty($sec['desc']) && empty($features) && empty($care);
                        @endphp

                        @if ($noSections && $raw !== '')
                            <div class="section-title">{{ $titles['desc'] }}</div>
                            <p class="mb-2" style="white-space:pre-line">{{ $raw }}</p>
                        @else
                            @if (!empty($sec['desc']))
                                <div class="section-title">{{ $titles['desc'] }}</div>
                                <p class="mb-2" style="white-space:pre-line">{{ $sec['desc'] }}</p>
                            @endif

                            @if (!empty($features))
                                <div class="section-title">{{ $titles['features'] }}</div>
                                <ul class="ul-check mb-2">
                                    @foreach ($features as $f)
                                        <li>{{ $f }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (!empty($care))
                                <div class="section-title">{{ $titles['care'] }}</div>
                                <ul class="ul-check mb-2">
                                    @foreach ($care as $c)
                                        <li>{{ $c }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                        {{-- ======= จบส่วนแยกหัวข้อ ======= --}}

                        {{-- ปุ่มหลัก (เดสก์ท็อป/แท็บเล็ต) --}}
                        <div class="btns">
                            @if ((int) $product->quantity <= 0)
                                <button class="btn btn-secondary w-50 btn-disabled"><i class="bi bi-cart-plus"></i>
                                    {{ __('messages.add_to_cart') }}</button>
                                <button class="btn btn-secondary w-50 btn-disabled"><i class="bi bi-bag-check"></i>
                                    {{ __('messages.buy_now') }}</button>
                            @else
                                <form action="{{ route('cart.store') }}" method="POST" class="flex-fill m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-cart w-100"><i
                                            class="bi bi-cart-plus me-1"></i>{{ __('messages.add_to_cart') }}</button>
                                </form>

                                <form action="{{ route('checkout.buy_now') }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-buy w-100"><i
                                            class="bi bi-bag-check me-1"></i>{{ __('messages.buy_now') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky CTA เฉพาะมือถือ --}}
        <div class="sticky-cta">
            <div class="d-flex gap-2">
                @if ((int) $product->quantity <= 0)
                    <button class="btn btn-secondary w-50 btn-disabled"><i class="bi bi-cart-plus"></i>
                        {{ __('messages.add_to_cart') }}</button>
                    <button class="btn btn-secondary w-50 btn-disabled"><i class="bi bi-bag-check"></i>
                        {{ __('messages.buy_now') }}</button>
                @else
                    <form action="{{ route('cart.store') }}" method="POST" class="flex-fill m-0 p-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="btn btn-cart w-100"><i class="bi bi-cart-plus"></i>
                            {{ __('messages.add_to_cart') }}</button>
                    </form>
                    <form action="{{ route('checkout.buy_now') }}" method="POST" class="flex-fill m-0 p-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn btn-buy w-100"><i class="bi bi-bag-check"></i>
                            {{ __('messages.buy_now') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- JS เล็ก ๆ สำหรับคัดลอกลิงก์ --}}
    <script>
        document.getElementById('copyLink')?.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
                const el = document.getElementById('copyLink');
                const old = el.innerHTML;
                el.innerHTML = '<i class="bi bi-check2-circle"></i>';
                setTimeout(() => el.innerHTML = old, 1000);
            } catch (e) {
                alert('คัดลอกลิงก์ไม่สำเร็จ');
            }
        });
    </script>
@endsection
