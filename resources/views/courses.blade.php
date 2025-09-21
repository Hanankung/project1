@extends('layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('CSS/product1.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
            /* ===== Styling เหมือนฝั่งสมาชิก (สcope แค่หน้านี้) ===== */
            .courses-page {
                --brand: #2e7d32;
                /* เขียวหลัก */
                --brand-dark: #27672a;
                /* เขียวเข้มตอน hover */
                --brand-ghost: rgba(46, 125, 50, .12);
                --ink: #1f2937;
                --muted: #667085;
            }

            .courses-page h2 {
                font-weight: 800;
                letter-spacing: .2px;
                background: linear-gradient(120deg, var(--brand), #000, var(--brand));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }

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

            .courses-page .text-primary {
                color: var(--brand) !important;
            }

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

            .courses-page .thumb-wrap {
                aspect-ratio: 4/3;
                background: #f7f8fa;
                overflow: hidden;
            }

            .courses-page .thumb-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform .35s ease;
                display: block;
            }

            .courses-page .course-card:hover .thumb-wrap img {
                transform: scale(1.04);
            }

            .courses-page .card-title {
                font-weight: 700;
                font-size: clamp(1.02rem, .4vw+1rem, 1.15rem);
                line-height: 1.25;
                margin-bottom: .35rem;
            }

            .courses-page .card-text {
                color: var(--muted) !important;
                font-size: .95rem;
            }

            .courses-page .row.g-4 {
                --bs-gutter-y: 1.5rem;
                --bs-gutter-x: 1.2rem;
            }
        </style>
    </head>

    <div class="container py-5 courses-page">
        {{-- หัวข้อกึ่งกลาง --}}
        <div class="position-relative mb-4">
            <h2 class="m-0 text-center fw-bold">{{ __('messages.courses_title') }}</h2>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 justify-content-center">
            @foreach ($courses as $course)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 course-card">
                        {{-- ไปหน้า “รายละเอียดคอร์ส (guest)” --}}
                        <a href="{{ route('guest.courses.show', $course->id) }}" class="d-block">
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
                                {{ __('messages.price') }} : {{ number_format($course->price) }} {{ __('messages.baht') }}
                            </p>

                            <a href="{{ route('guest.courses.show', $course->id) }}"
                                class="btn btn-outline-primary mt-auto">
                                {{ __('messages.learn_more') }}
                            </a>

                            {{-- ปุ่มจอง: แสดงเพียงอันเดียว --}}
                            @auth
                                <a href="{{ route('member.course.booking', $course->id) }}"
                                    class="btn btn-primary btn-sm mt-2 w-100">
                                    + {{ __('messages.book_course') }}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary btn-sm mt-2 w-100" data-auth="required"
                                    data-auth-title="{{ __('messages.auth_required_title_booking') }}"
                                    data-auth-message="{{ __('messages.auth_required_msg_booking') }}">
                                    + {{ __('messages.book_course') }}
                                </button>

                            @endauth
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
