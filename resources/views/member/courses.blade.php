@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/product1.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    </head>
    <div class="container py-5">
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
                            <p class="fw-bold text-primary">{{ __('messages.price') }} :
                                {{ number_format($course->price) }}
                                {{ __('messages.baht') }}</p>
                            <a href="{{ route('member.course.detail', $course->id) }}"
                                class="btn btn-outline-primary mt-auto">{{ __('messages.learn_more') }}</a>
                            <a href="{{ route('member.course.booking', $course->id) }}"
                                class="btn btn-primary btn-sm mt-2 w-100"> + {{ __('messages.book_course') }}</a>
                            {{-- <a href="{{ route('create')}}" class="btn btn-primary mb-3">+ Create New Post</a> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($courses->isEmpty())
            <div class="alert alert-info text-center">
                {{ __('messages.no_courses') }}
            </div>
        @endif
    </div>
@endsection
