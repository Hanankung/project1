@extends('admin.layout')

@section('content')
    @php
        $price = is_numeric($post->price) ? number_format($post->price, 2) : $post->price;
    @endphp

    <div class="container my-4">
        <style>
            :root {
                --ink: #1f2937;
                --muted: #6b7280;
                --brand: #6b4e2e;
                --paper: #faf7f2;
            }

            .show-wrap {
                border: 1px solid #eee;
                border-radius: 16px;
                background: #fff;
                box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
                overflow: hidden;
            }

            .show-head {
                background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
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

            .main-img {
                width: 100%;
                aspect-ratio: 4/3;
                object-fit: cover;
                display: block;
                background: #f6f6f6;
                border-radius: 12px;
            }

            .box-left {
                padding: 18px;
                border-right: 1px solid #f3f3f3;
            }

            .meta dt {
                color: var(--muted)
            }

            .price {
                font-weight: 800;
                color: var(--brand)
            }

            .lang-block {
                border: 1px solid #f1f1f1;
                border-radius: 12px;
                padding: 12px 14px;
                background: #fff;
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
                    <h2 class="title">{{ $post->course_name }}</h2>
                    <p class="subtitle mb-0">{{ $post->course_name_ENG ?: '—' }} • {{ $post->course_name_MS ?: '—' }}</p>
                </div>
                <div class="actions d-flex gap-2">
                    <a href="{{ route('admin.course') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                    <a href="{{ route('admin.edit_course', $post) }}" class="btn btn-outline-warning">
                        <i class="bi bi-pencil-square"></i> แก้ไข
                    </a>
                </div>
            </div>

            <div class="row g-0">
                {{-- Left: Image --}}
                <div class="col-12 col-lg-5 box-left">
                    @if ($post->course_image)
                        <img class="main-img" src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}">
                    @else
                        <div class="main-img d-flex align-items-center justify-content-center">
                            <small class="text-muted">No image</small>
                        </div>
                    @endif

                    {{-- Quick meta under image --}}
                    <dl class="row meta mt-3 mb-0">
                        <dt class="col-sm-4">ราคา</dt>
                        <dd class="col-sm-8"><span class="price">฿ {{ $price }}</span> / คน</dd>
                    </dl>
                </div>

                {{-- Right: Details --}}
                <div class="col-12 col-lg-7 p-3 p-lg-4">
                    <div class="row g-3">
                        {{-- Thai --}}
                        <div class="col-12">
                            <div class="lang-block mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-th">ไทย</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-th"><i
                                            class="bi bi-clipboard"></i> คัดลอก</button>
                                </div>
                                <div class="mt-2 fw-semibold">{{ $post->course_name ?: '—' }}</div>
                                <div id="desc-th" class="mt-1">{{ $post->course_detail ?: '—' }}</div>
                            </div>
                        </div>

                        {{-- English --}}
                        <div class="col-12">
                            <div class="lang-block mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-en">English</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-en"><i
                                            class="bi bi-clipboard"></i> Copy</button>
                                </div>
                                <div class="mt-2 fw-semibold">{{ $post->course_name_ENG ?: '—' }}</div>
                                <div id="desc-en" class="mt-1">{{ $post->course_detail_ENG ?: '—' }}</div>
                            </div>
                        </div>

                        {{-- Malay --}}
                        <div class="col-12">
                            <div class="lang-block">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="lang-chip chip-ms">Malay</span>
                                    <button type="button" class="copy-btn" data-copy="#desc-ms"><i
                                            class="bi bi-clipboard"></i> Salin</button>
                                </div>
                                <div class="mt-2 fw-semibold">{{ $post->course_name_MS ?: '—' }}</div>
                                <div id="desc-ms" class="mt-1">{{ $post->course_detail_MS ?: '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom actions on mobile --}}
                    <div class="d-lg-none mt-3 d-flex gap-2">
                        <a href="{{ route('admin.edit_course', $post) }}" class="btn btn-warning flex-grow-1"><i
                                class="bi bi-pencil-square"></i> แก้ไข</a>
                        <a href="{{ route('admin.course') }}" class="btn btn-secondary flex-grow-1"><i
                                class="bi bi-arrow-left"></i> กลับ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copy to clipboard (ใช้ SweetAlert2 ถ้ามี) --}}
    <script>
        document.querySelectorAll('[data-copy]').forEach(btn => {
            btn.addEventListener('click', () => {
                const sel = btn.getAttribute('data-copy');
                const el = document.querySelector(sel);
                if (!el) return;
                const text = (el.innerText || el.textContent || '').trim();
                if (!text) return;
                navigator.clipboard.writeText(text).then(() => {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'คัดลอกแล้ว',
                            text: 'คัดลอกเนื้อหาไปยังคลิปบอร์ด',
                            timer: 1200,
                            showConfirmButton: false,
                            position: 'center'
                        });
                    } else {
                        btn.textContent = 'Copied!';
                        setTimeout(() => location.reload(), 600);
                    }
                });
            });
        });
    </script>
@endsection
