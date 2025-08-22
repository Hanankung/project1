@extends('layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/product1.css') }}">
</head>
<div class="container py-5">
    <h2 class="mb-4 text-center">คอร์สเรียนทั้งหมด</h2>

    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($course->course_image)
                        <img src="{{ asset($course->course_image) }}" class="card-img-top" alt="{{ $course->course_name }}">
                    @else
                        <img src="https://via.placeholder.com/400x250?text=No+Image" class="card-img-top" alt="No Image">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $course->course_name }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($course->course_detail, 100) }}
                        </p>
                        <p class="fw-bold text-primary">ราคา: {{ number_format($course->price) }} บาท</p>
                        <a href="{{ route('guest.courses.show', $course->id) }}" class="btn btn-outline-primary mt-auto">ดูรายละเอียด</a>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm mt-2 w-100">+จองคอร์สเรียน</a>
                        {{-- <a href="{{ route('create')}}" class="btn btn-primary mb-3">+ Create New Post</a> --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($courses->isEmpty())
        <div class="alert alert-info text-center">
            ยังไม่มีคอร์สเรียนในขณะนี้
        </div>
    @endif
</div>
@endsection
