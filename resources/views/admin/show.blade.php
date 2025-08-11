@extends('admin.layout')

@section('content')
<h5>{{ $post->product_name }}</h5>
        <p>{{ $post->description }}</p>
        <p>Price: {{ $post->price }}</p>
        <p>quantity: {{ $post->quantity }}</p>
        <p>material: {{ $post->material }}</p>
        <p>size: {{ $post->size }}</p>
        @if($post->product_image)
    <img src="{{ asset($post->product_image) }}" alt="{{ $post->product_name }}" width="200">
    @else
    <p>No image</p>
    @endif

    <a href="{{ route('admin.product') }}" class="btn btn-secondary">Back</a>

@endsection