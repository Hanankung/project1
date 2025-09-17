@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/product1.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
            /* ===== Styling เฉพาะหน้านี้ ===== */
            .courses-page {
                --brand: #2e7d32;
                /* สีเขียวหลักที่ต้องการ */
                --brand-dark: #27672a;
                /* สีตอน hover */
                --brand-ghost: rgba(46, 125, 50, .12);
                --ink: #1f2937;
                --muted: #667085;
            }

            /* หัวข้อใหญ่แบบไล่เฉดโทนเขียว */
            .courses-page h2 {
                font-weight: 800;
                letter-spacing: .2px;
                background: linear-gradient(120deg, var(--brand), #000000, var(--brand));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }

            /* ปุ่ม: override ให้เป็นเขียว */
            .courses-page .btn {
                border-radius: 12px;
            }

            .courses-page .btn-primary {
                background: var(--brand);
                border-color: var(--brand);
                box-shadow: 0 10px 24px rgba(46, 125, 50, .18);
            }

            .courses-page .btn-primary:hover {
                background: var(--brand-dark);
                border-color: var(--brand-dark);
            }

            .courses-page .btn-outline-primary {
                color: var(--brand);
                border-color: var(--brand);
            }

            .courses-page .btn-outline-primary:hover {
                background: var(--brand);
                border-color: var(--brand);
                color: #fff;
            }

            /* ข้อความที่ใช้ .text-primary ให้เป็นเขียวเดียวกัน (scoped แค่ใน .courses-page) */
            .courses-page .text-primary {
                color: var(--brand) !important;
            }

            /* การ์ดคอร์ส */
            .courses-page .course-card {
                border-radius: 16px;
                border: 1px solid #eef0f2;
                overflow: hidden;
                box-shadow: 0 14px 34px rgba(0, 0, 0, .06);
                transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
                background: #fff;
            }

            .courses-page .course-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 44px rgba(0, 0, 0, .10);
                border-color: var(--brand-ghost);
            }

            /* รูปคอร์สฟิกสัดส่วน + ซูมเบา ๆ ตอน hover */
            .courses-page .thumb-wrap {
                aspect-ratio: 4/3;
                background: #f7f8fa;
                overflow: hidden;
            }

            .courses-page .thumb-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform .35s ease;
            }

            .courses-page .course-card:hover .thumb-wrap img {
                transform: scale(1.04);
            }

            /* เนื้อในการ์ด */
            .courses-page .card-title {
                font-weight: 700;
                font-size: clamp(1.02rem, .4vw + 1rem, 1.15rem);
                line-height: 1.25;
                margin-bottom: .35rem;
            }

            .courses-page .card-text {
                color: var(--muted) !important;
                font-size: .95rem;
            }

            /* ปรับ spacing */
            .courses-page .row.g-4 {
                --bs-gutter-y: 1.5rem;
                --bs-gutter-x: 1.2rem;
            }
        </style>
    </head>

    <div class="container py-5 courses-page">
        {{-- หัวข้อกึ่งกลาง + ปุ่มประวัติชิดขวา (เดสก์ท็อป) --}}
        <div class="position-relative mb-4">
            <h2 class="m-0 text-center fw-bold">{{ __('messages.courses_title') }}</h2>

            {{-- ปุ่มชิดขวา: แสดงเฉพาะ md ขึ้นไป --}}
            <a href="{{ route('member.course.booking.list') }}"
                class="btn btn-primary btn-sm shadow-sm position-absolute top-50 translate-middle-y end-0 d-none d-md-inline-flex">
                <i class="bi bi-journal-text me-1"></i>
                {{ __('messages.booking_history_menu') }}
            </a>
        </div>

        {{-- ปุ่มแบบเต็มความกว้างสำหรับจอเล็ก (มือถือ) --}}
        <div class="d-grid gap-2 d-md-none mb-3">
            <a href="{{ route('member.course.booking.list') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-journal-text me-1"></i>
                {{ __('messages.booking_history_menu') }}
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 justify-content-center">
            @foreach ($courses as $course)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 course-card">
                        <a href="{{ route('member.course.detail', $course->id) }}" class="d-block">
                            <div class="thumb-wrap">
                                @if ($course->course_image)
                                    <img src="{{ asset($course->course_image) }}" alt="{{ $course->course_name }}"
                                        loading="lazy">
                                @else
                                    <img src="https://via.placeholder.com/400x250?text=No+Image" alt="No Image"
                                        loading="lazy">
                                @endif
                            </div>
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $course->name_i18n }}</h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($course->detail_i18n, 100) }}
                            </p>

                            <p class="fw-bold text-primary mb-2">
                                {{ __('messages.price') }} : {{ number_format($course->price) }}
                                {{ __('messages.baht') }}
                            </p>

                            <a href="{{ route('member.course.detail', $course->id) }}"
                                class="btn btn-outline-primary mt-auto">
                                {{ __('messages.learn_more') }}
                            </a>

                            <a href="{{ route('member.course.booking', $course->id) }}"
                                class="btn btn-primary btn-sm mt-2 w-100">
                                + {{ __('messages.book_course') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($courses->isEmpty())
            <div class="alert alert-info text-center mt-4">
                {{ __('messages.no_courses') }}
            </div>
        @endif
    </div>
@endsection
