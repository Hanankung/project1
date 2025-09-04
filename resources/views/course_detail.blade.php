@extends('layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <div class="container py-5">
        <div class="row">
            <!-- รูปหลัก -->
            <div class="col-md-6 d-flex justify-content-center">
                @if ($course->course_image)
                    <img src="{{ asset($course->course_image) }}" class="rounded shadow" alt="{{ $course->course_name }}"
                        style="width: 300px; height: 300px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/300x300?text=No+Image" class="rounded shadow" alt="No Image"
                        style="width: 300px; height: 300px; object-fit: cover;">
                @endif
            </div>


            <!-- รายละเอียดคอร์ส -->
            <div class="col-md-6">
                <h2>{{ $course->name_i18n }}</h2>
                <p class="text-muted">{{ $course->detail_i18n }}</p>
                <h4 class="text-primary">{{ __('messages.price') }}: {{ number_format($course->price) }} {{ __('messages.baht') }}</h4>

                <a href="{{ route('login') }} " class="btn btn-success mt-3">{{ __('messages.book_course') }}</a>
                <a href="{{ route('guest.courses') }}" class="btn btn-secondary mt-3">{{ __('messages.back_to_list') }}</a>

            </div>
        </div>

        <!-- Gallery Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="fw-bold mb-4">{{ __('messages.gallery') }} :</h4>
            </div>

            <div class="row row-cols-2 row-cols-md-5 g-3">
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail7.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 1">
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail1.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 2">
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail2.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 3">
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail3.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 4">
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail6.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 5">
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
