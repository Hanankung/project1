{{-- resources/views/admin/product.blade.php --}}
@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('CSS/product.css') }}">
    {{-- ถ้ายังไม่ได้ใส่ bootstrap-icons ใน layout แนะนำเพิ่ม --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"> --}}

    @php
        $warnings = session('low_stock_warnings');
        $total = $posts->count();
        $lowCount = $posts->filter(fn($p) => (int) $p->quantity <= (int) ($p->low_stock_threshold ?? 5))->count();
        $inStock = $posts->where('quantity', '>', 0)->count();
    @endphp

    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --paper: #faf7f2;
            --brand: #6b4e2e;
            --brand-2: #8a6b47;
            --gold: #d5b66f;
            --danger: #e03131;
            --ok: #16a34a;
        }

        .admin-hero {
            background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
        }

        .stat-pill {
            border: 1px solid #eee;
            background: #fff;
            border-radius: 14px;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .03);
        }

        .stat-pill .num {
            font-weight: 700;
            font-size: 18px;
            color: var(--ink)
        }

        .stat-pill.low {
            border-color: #ffe3e3;
            background: #fff5f5
        }

        .stat-pill.low .num {
            color: var(--danger)
        }

        .btn-create {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(139, 92, 45, .25);
        }

        .btn-create:hover {
            filter: brightness(1.05)
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            transition: .2s transform, .2s box-shadow;
            background: #fff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
            height: 100%;
            /* สำคัญ: ให้การ์ดสูงเต็มคอลัมน์ */
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, .07);
        }

        /* รูปเท่ากันทุกใบ */
        .pc-image {
            width: 100%;
            aspect-ratio: 4/3;
            /* 16/9 ก็ได้ถ้าชอบ */
            object-fit: cover;
            display: block;
            background: #f6f6f6;
            border-bottom: 1px solid #eee;
        }

        .pc-body {
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pc-title {
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .pc-meta {
            color: var(--muted);
            font-size: 13px;
        }

        .pc-price {
            font-weight: 700;
            color: var(--brand);
        }

        .pc-actions .btn {
            border-radius: 10px;
        }

        .ribbon {
            position: absolute;
            top: 12px;
            left: -8px;
            background: #e03131;
            color: #fff;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            transform: rotate(-8deg);
            box-shadow: 0 6px 14px rgba(224, 49, 49, .3);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .stock-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .progress {
            height: 8px;
            border-radius: 999px;
            background: #f1f1f1;
        }

        .progress-bar {
            background: linear-gradient(90deg, #fecaca, #f87171);
        }

        .progress-bar.safe {
            background: linear-gradient(90deg, #bbf7d0, #22c55e);
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            border-radius: 12px !important;
            padding: 10px 14px !important;
            border: 1px solid #e5e7eb !important;
        }

        .badge-soft {
            background: #f3f4f6;
            color: #374151;
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 600;
        }

        /* ทำคอลัมน์ยืดสูงเท่ากันทุกใบ */
        .product-item {
            display: flex;
        }

        /* จำกัดบรรทัดคำอธิบายให้เท่ากัน */
        .pc-desc {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: calc(1.25rem * 3 + 6px);
        }

        /* ให้ลูกทุกตัวของ .pc-actions (ทั้ง <a> และ <form>) กว้างเท่ากัน */
        .pc-actions {
            display: flex;
            gap: 8px;
            width: 100%;
        }

        .pc-actions>* {
            flex: 1;
            /* ทุก child (a, form) กว้างเท่ากัน */
        }

        .pc-actions .btn {
            width: 100%;
            /* ปุ่มข้างในเต็มช่อง */
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 0;
            border-radius: 10px;
            white-space: nowrap;
        }
    </style>

    {{-- Flash: สินค้าใกล้หมด --}}
    @if (is_iterable($warnings))
        <div class="alert alert-danger">
            <strong>สินค้าใกล้หมด!</strong>
            <ul class="mb-0">
                @foreach ($warnings as $w)
                    <li>
                        [#{{ $w['id'] }}] {{ $w['name'] }} — คงเหลือ <strong>{{ $w['qty'] }}</strong> ชิ้น
                        (เกณฑ์แจ้งเตือน {{ $w['threshold'] }} ชิ้น)
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    {{-- Hero / Summary --}}
    <div class="admin-hero mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">
                    สินค้าทั้งหมด
                </h1>
                @if ($lowCount > 0)
                    <span class="badge bg-danger">ใกล้หมด {{ $lowCount }} รายการ</span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="stat-pill">
                    <i class="bi bi-box-seam"></i>
                    <span>ทั้งหมด <span class="num">{{ $total }}</span></span>
                </div>
                <div class="stat-pill">
                    <i class="bi bi-check2-circle" style="color:var(--ok)"></i>
                    <span>มีสต็อก <span class="num">{{ $inStock }}</span></span>
                </div>
                <div class="stat-pill low">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>ใกล้หมด <span class="num">{{ $lowCount }}</span></span>
                </div>
                {{-- 🔽 ปุ่มคลังสินค้า --}}
                <a href="{{ route('admin.product.archived') }}" class="btn btn-outline-secondary">
                    คลังสินค้า
                </a>
                <a href="{{ route('create') }}" class="btn btn-create">
                    <i class="bi bi-plus-lg"></i> + เพิ่มสินค้า
                </a>
            </div>
        </div>

        {{-- <div class="filter-bar mt-3">
        <input type="search" id="q" class="form-control search-input" placeholder="ค้นหาชื่อสินค้า / วัสดุ / ขนาด... (กรองในหน้า)">
        <span class="badge-soft">คลิกการ์ดเพื่อโฟกัสข้อมูล</span>
    </div> --}}
    </div>

    {{-- Product Grid --}}
    @if ($posts->count())
        <div class="row g-3 align-items-stretch" id="productGrid">
            @foreach ($posts as $post)
                @php
                    $threshold = (int) ($post->low_stock_threshold ?? 5);
                    $qty = (int) $post->quantity;
                    $isLow = $qty <= $threshold;
                    $ratio = $threshold > 0 ? min(100, max(0, round(($qty / max($threshold, 1)) * 100))) : 100;
                @endphp

                <div class="col-12 col-sm-6 col-lg-3 product-item d-flex"
                    data-keywords="{{ \Illuminate\Support\Str::lower(($post->product_name ?? '') . ' ' . ($post->material ?? '') . ' ' . ($post->size ?? '')) }}">
                    <div class="product-card @if ($isLow) border-danger @endif">
                        @if ($isLow)
                            <div class="ribbon">ใกล้หมด</div>
                        @endif

                        {{-- Image --}}
                        @if ($post->product_image)
                            <img class="pc-image" src="{{ asset($post->product_image) }}" alt="{{ $post->product_name }}">
                        @else
                            <div class="pc-image d-flex align-items-center justify-content-center">
                                <small class="text-muted">No image</small>
                            </div>
                        @endif

                        {{-- Body --}}
                        <div class="pc-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="pc-title">{{ $post->product_name }}</div>
                                    <div class="pc-meta">
                                        <span class="me-2">วัสดุ: <strong>{{ $post->material ?: '-' }}</strong></span>
                                        <span>ขนาด: <strong>{{ $post->size ?: '-' }}</strong></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="pc-price">฿ {{ number_format($post->price, 2) }}</div>
                                    <small class="text-muted">ต่อชิ้น</small>
                                </div>
                            </div>

                            <p class="pc-desc mt-2 mb-3 text-secondary">
                                {{ \Illuminate\Support\Str::limit($post->description, 200) }}
                            </p>

                            {{-- Stock line + progress --}}
                            <div class="stock-line mb-2" title="เกณฑ์ต่ำสุด {{ $threshold }} ชิ้น">
                                <span class="text-muted">คงเหลือ: <strong>{{ $qty }}</strong></span>
                                @if ($threshold > 0)
                                    <div class="flex-grow-1 ms-3">
                                        <div class="progress">
                                            <div class="progress-bar @if (!$isLow) safe @endif"
                                                role="progressbar" style="width: {{ $ratio }}%"
                                                aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if ($isLow)
                                <div class="text-danger small">เกณฑ์แจ้งเตือน {{ $threshold }} ชิ้น</div>
                            @endif

                            {{-- Actions (ชิดก้นการ์ดด้วย mt-auto) --}}
                            <div class="pc-actions mt-auto">
                                <a href="{{ route('admin.show', $post) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i> ดูสินค้า
                                </a>
                                <a href="{{ route('admin.edit', $post) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                </a>
                                <form action="{{ route('admin.delete', $post) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" data-confirm-delete
                                        data-entity="สินค้า">
                                        <i class="bi bi-archive"></i> จัดเก็บ
                                    </button>
                                </form>
                            </div>





                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">ยังไม่มีโพสในระบบ</div>
    @endif

    {{-- ค้นหาแบบเบา ๆ ในหน้า --}}
    <script>
        (function() {
            const q = document.getElementById('q');
            if (!q) return;
            const items = Array.from(document.querySelectorAll('#productGrid .product-item'));
            q.addEventListener('input', function() {
                const needle = this.value.trim().toLowerCase();
                items.forEach(el => {
                    const hay = el.getAttribute('data-keywords') || '';
                    el.style.display = hay.includes(needle) ? '' : 'none';
                });
            });
        })();
    </script>
@endsection
