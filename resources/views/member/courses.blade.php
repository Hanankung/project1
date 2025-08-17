@extends('member.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">คอร์สเรียนทั้งหมด</h2>

    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4 mb-3">
                <div class="card">
                    @if($course->course_image)
                        <img src="{{ asset($course->course_image) }}" class="card-img-top" alt="{{ $course->course_name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->course_name }}</h5>
                        <p class="card-text">{{ $course->course_detail }}</p>
                        <p><strong>ราคา:</strong> {{ $course->price }} บาท</p>
                        <a href="#" class="btn btn-outline-primary mt-auto">ดูรายละเอียด</a>
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
