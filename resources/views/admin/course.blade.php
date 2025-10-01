{{-- resources/views/admin/course_index.blade.php --}}
@extends('admin.layout')

@section('content')

    @php
        $total = $posts->count();
    @endphp

    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --paper: #faf7f2;
            --brand: #6b4e2e;
            --brand2: #8a6b47;
        }

        .admin-hero {
            background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
        }

        .stat-pill {
            border: 1px solid #eee;
            background: #fff;
            border-radius: 14px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .03);
        }

        .stat-pill .num {
            font-weight: 700;
            color: var(--ink)
        }

        .btn-create {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand2) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(139, 92, 45, .25);
        }

        .btn-create:hover {
            filter: brightness(1.05);
            color: #fff
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

        .course-card {
            border: 1px solid #eee;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
            transition: .2s transform, .2s box-shadow;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, .07);
        }

        .cc-img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
            background: #f6f6f6;
            border-bottom: 1px solid #eee;
        }

        .cc-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .cc-title {
            font-weight: 800;
            color: var(--ink);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: calc(1.3rem * 2);
        }

        .cc-desc {
            color: var(--muted);
            margin: 8px 0 10px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: calc(1.15rem * 3);
        }

        .cc-meta {
            color: var(--muted);
            font-size: .9rem;
        }

        .cc-price {
            font-weight: 800;
            color: var(--brand);
        }

        .cc-actions .btn {
            border-radius: 10px;
        }

        .badge-soft {
            background: #f3f4f6;
            color: #374151;
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 600;
        }

        .course-item {
            display: flex;
        }

        /* ทำให้คอลัมน์ยืดสูงเท่ากัน */
    </style>

    {{-- Top success (ถ้ามี) --}}
    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    {{-- HERO / SUMMARY --}}
    <div class="admin-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">คอร์สเรียน</h1>
                <span class="badge-soft">จัดการคอร์สเรียน</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="stat-pill">
                    <i class="bi bi-mortarboard"></i> ทั้งหมด <span class="num">{{ $total }}</span>
                </div>
                <a href="{{ route('create_course') }}" class="btn btn-create">
                    <i class="bi bi-plus-lg"></i>+ เพิ่มคอร์สเรียน
                </a>
            </div>
        </div>

        {{-- <div class="filter-bar mt-3">
            <input type="search" id="q" class="form-control search-input"
                placeholder="ค้นหาชื่อคอร์ส / คำอธิบาย... (กรองในหน้า)">
            <span class="badge-soft">เคล็ดลับ: พิมพ์บางคำก็เจอ</span>
        </div> --}}
    </div>

    {{-- GRID --}}
    @if ($posts->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3" id="courseGrid">
            @foreach ($posts as $post)
                <div class="course-item col"
                    data-keywords="{{ \Illuminate\Support\Str::lower(($post->course_name ?? '') . ' ' . ($post->course_detail ?? '')) }}">
                    <div class="course-card">
                        {{-- Image --}}
                        @if ($post->course_image)
                            <img class="cc-img" src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}"
                                loading="lazy">
                        @else
                            <div class="cc-img d-flex align-items-center justify-content-center">
                                <small class="text-muted">No image</small>
                            </div>
                        @endif

                        {{-- Body --}}
                        <div class="cc-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <div class="cc-title">{{ $post->course_name }}</div>
                                    <div class="cc-desc">
                                        {{ \Illuminate\Support\Str::limit($post->course_detail, 220) }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="cc-price">฿ {{ number_format($post->price, 2) }}</div>
                                    <div class="cc-meta">ต่อคน</div>
                                </div>
                            </div>

                            {{-- Actions (ชิดก้นการ์ดด้วย mt-auto) --}}
                            <div class="cc-actions d-flex gap-2 mt-auto">
                                <a href="{{ route('admin.showcourse', $post) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('admin.edit_course', $post) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.delete_course', $post) }}" method="POST" class="ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete
                                        data-entity="คอร์สเรียน"> {{-- ไทยล้วน --}}
                                        <i class="bi bi-trash3"></i> Delete
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

    {{-- Client-side filter --}}
    <script>
        (function() {
            const q = document.getElementById('q');
            if (!q) return;
            const items = Array.from(document.querySelectorAll('#courseGrid .course-item'));
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
