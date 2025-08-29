@extends('admin.layout')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $post->product_name }}</h5>
            <h5 class="mb-0">{{ $post->product_name_ENG }}</h5>
            <h5 class="mb-0">{{ $post->product_name_MS }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $post->description }}</p>
            <p><strong>Description (English):</strong> {{ $post->description_ENG }}</p>
            <p><strong>Description (Malay):</strong> {{ $post->description_MS }}</p>
            <p><strong>Price:</strong> {{ $post->price }} บาท</p>
            <p><strong>Quantity:</strong> {{ $post->quantity }}</p>
            <p><strong>Material:</strong> {{ $post->material }}</p>
            <p><strong>Material (English):</strong> {{ $post->material_ENG }}</p>
            <p><strong>Material (Malay):</strong> {{ $post->material_MS }}</p>
            <p><strong>Size:</strong> {{ $post->size }}</p>

            @if($post->product_image)
                <div class="mb-3">
                    <img src="{{ asset($post->product_image) }}" alt="{{ $post->product_name }}" class="img-fluid rounded" style="max-width: 200px;">
                </div>
            @else
                <p class="text-muted">No image</p>
            @endif

            <a href="{{ route('admin.product') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
