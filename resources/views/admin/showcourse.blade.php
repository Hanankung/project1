@extends('admin.layout')

@section('content')
<h1>{{ $post->course_name }}</h1>
<p>{{ $post->course_detail }}</p>
<p>Price: {{ $post->price }}</p>

@if($post->course_image)
    <img src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}" width="200">
@else
    <p>No image</p>
@endif

<a href="{{ route('admin.course') }}" class="btn btn-secondary">Back</a>
@endsection
