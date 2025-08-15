@extends('admin.layout')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="row g-0 align-items-center">
            {{-- รูปภาพ --}}
            <div class="col-md-4 text-center p-3">
                @if($post->course_image)
                    <img src="{{ asset($post->course_image) }}" 
                         alt="{{ $post->course_name }}" 
                         class="img-fluid rounded" 
                         style="max-width: 250px;">
                @else
                    <p class="text-muted">No image</p>
                @endif
            </div>

            {{-- เนื้อหา --}}
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $post->course_name }}</h3>
                    <p class="card-text">{{ $post->course_detail }}</p>
                    <p class="card-text">
                        <strong>Price:</strong> 
                        <span class="text-success">{{ $post->price }} บาท</span>
                    </p>

                    <a href="{{ route('admin.course') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
