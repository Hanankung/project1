{{-- resources/views/admin/show.blade.php --}}
@extends('admin.layout')

@section('content')
    @php
        $threshold = (int) ($post->low_stock_threshold ?? 5);
        $qty = (int) $post->quantity;
        $isLow = $qty <= $threshold;
        $ratio = $threshold > 0 ? min(100, max(0, round(($qty / max($threshold, 1)) * 100))) : 100;
    @endphp

    <div class="container my-4">
        <style>
            :root {
                --ink: #1f2937;
                --muted: #6b7280;
                --brand: #6b4e2e;
                --brand2: #8a6b47;
                --danger: #e03131;
                --ok: #16a34a;
            }

            .show-wrap {
                border: 1px solid #eee;
                border-radius: 16px;
                background: #fff;
                box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
                overflow: hidden;
            }

            .show-head {
                background: linear-gradient(135deg, #fff 0%, #faf7f2 100%);
                border-bottom: 1px solid #f0f0f0;
                padding: 16px 18px;
            }

            .title {
                font-weight: 800;
                letter-spacing: .2px;
                color: var(--ink);
                margin: 0;
            }

            .subtitle {
                color: var(--muted);
                margin: 0
            }

            .img-box {
                position: relative;
                border-right: 1px solid #f3f3f3;
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
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                box-shadow: 0 6px 14px rgba(224, 49, 49, .3);
            }

            .main-img {
                width: 100%;
                aspect-ratio: 4/3;
                object-fit: cover;
                display: block;
                background: #f6f6f6;
            }

            .meta dt {
                color: var(--muted);
            }

            .price {
                font-weight: 800;
                color: var(--brand)
            }

            .stock-line {
                display: flex;
                align-items: center;
                gap: 10px
            }

            .progress {
                height: 8px;
                border-radius: 999px;
                background: #f1f1f1
            }

            .progress-bar {
                background: linear-gradient(90deg, #fecaca, #f87171)
            }

            .progress-bar.safe {
                background: linear-gradient(90deg, #bbf7d0, #22c55e)
            }

            .lang-block {
                border: 1px solid #f1f1f1;
                border-radius: 12px;
                padding: 12px 14px;
            }

            .lang-chip {
                display: inline-flex;
                gap: 8px;
                align-items: center;
                padding: 6px 10px;
                border-radius: 999px;
                font-weight: 700
            }

            .chip-th {
                background: #eef2ff;
                color: #1e3a8a
            }

            .chip-en {
                background: #ecfeff;
                color: #155e75
            }

            .chip-ms {
                background: #ecfdf5;
                color: #065f46
            }

            .copy-btn {
                border: none;
                background: #f3f4f6;
                border-radius: 8px;
                padding: 6px 10px;
                font-size: 12px
            }

            .actions .btn {
                border-radius: 10px
            }
        </style>

        <div class="show-wrap">
            {{-- Header --}}
            <div class="show-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="title">{{ $post->product_name }}</h2>
                    <p class="subtitle mb-0">{{ $post->product_name_ENG ?: '—' }} • {{ $post->product_name_MS ?: '—' }}</p>
                </div>
                <div class="d-flex gap-2 actions">
                    <a href="{{ route('admin.product') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                        กลับ</a>
                    <a href="{{ route('admin.edit', $post) }}" class="btn btn-outline-warning"><i
                            class="bi bi-pencil-square"></i> แก้ไข</a>
                    <form action="{{ route('admin.delete', $post) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" data-confirm-delete>
                            <i class="bi bi-trash3"></i> ลบ
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-0">
                {{-- Left: Image --}}
                <div class="col-12 col-lg-5 img-box p-3 p-lg-4">
                    @if ($isLow)
                        <div class="ribbon">ใกล้หมด</div>
                    @endif
                    @if ($post->product_image)
                        <img class="main-img rounded" src="{{ asset('storage/' . $post->product_image) }}"
                            alt="{{ $post->product_name }}">
                    @else
                        <div class="main-img d-flex align-items-center justify-content-center rounded">
                            <small class="text-muted">No image</small>
                        </div>
                    @endif

                    {{-- Stock status --}}
                    <div class="mt-3">
                        <div class="stock-line">
                            <div class="text-muted">คงเหลือ: <strong>{{ $qty }}</strong></div>
                            <div class="flex-grow-1">
                                <div class="progress">
                                    <div class="progress-bar @if (!$isLow) safe @endif"
                                        role="progressbar" style="width: {{ $ratio }}%"
                                        aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            เกณฑ์แจ้งเตือน: {{ $threshold }} ชิ้น
                        </small>
                    </div>
                </div>

                {{-- Right: Details --}}
                <div class="col-12 col-lg-7 p-3 p-lg-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <dl class="row meta">
                                <dt class="col-sm-3">ราคา</dt>
                                <dd class="col-sm-9"><span class="price">฿ {{ number_format($post->price, 2) }}</span> /
                                    ชิ้น</dd>

                                <dt class="col-sm-3">วัสดุ</dt>
                                <dd class="col-sm-9">{{ $post->material ?: '—' }}</dd>

                                <dt class="col-sm-3">วัสดุ (EN / MS)</dt>
                                <dd class="col-sm-9">
                                    {{ $post->material_ENG ?: '—' }} <span class="text-muted">|</span>
                                    {{ $post->material_MS ?: '—' }}
                                </dd>

                                <dt class="col-sm-3">ขนาด</dt>
                                <dd class="col-sm-9">{{ $post->size ?: '—' }}</dd>
                            </dl>
                        </div>

                        {{-- Descriptions (3 languages) --}}
                        <div class="col-12">
                            <div class="lang-block mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-th">ไทย</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-th"><i
                                            class="bi bi-clipboard"></i> คัดลอก</button>
                                </div>
                                <div id="desc-th" class="mt-2">{{ $post->description ?: '—' }}</div>
                            </div>

                            <div class="lang-block mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-en">English</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-en"><i
                                            class="bi bi-clipboard"></i> Copy</button>
                                </div>
                                <div id="desc-en" class="mt-2">{{ $post->description_ENG ?: '—' }}</div>
                            </div>

                            <div class="lang-block">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-ms">Malay</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-ms"><i
                                            class="bi bi-clipboard"></i> Salin</button>
                                </div>
                                <div id="desc-ms" class="mt-2">{{ $post->description_MS ?: '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom actions on mobile --}}
                    <div class="d-lg-none mt-3 d-flex gap-2">
                        <a href="{{ route('admin.edit', $post) }}" class="btn btn-warning flex-grow-1"><i
                                class="bi bi-pencil-square"></i> แก้ไข</a>
                        <a href="{{ route('admin.product') }}" class="btn btn-secondary flex-grow-1"><i
                                class="bi bi-arrow-left"></i> กลับ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copy to clipboard --}}
    <script>
        document.querySelectorAll('[data-copy]').forEach(btn => {
            btn.addEventListener('click', () => {
                const sel = btn.getAttribute('data-copy');
                const el = document.querySelector(sel);
                if (!el) return;
                const text = (el.innerText || el.textContent || '').trim();
                if (!text) return;
                navigator.clipboard.writeText(text).then(() => {
                    // ถ้ามี SweetAlert2 อยู่แล้ว จะเด้งกลางจอ
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'คัดลอกแล้ว',
                            text: 'ข้อความถูกคัดลอกไปยังคลิปบอร์ด',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    } else {
                        btn.textContent = 'Copied!';
                        setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy',
                            1200);
                    }
                });
            });
        });
    </script>
@endsection
