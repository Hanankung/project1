@extends('admin.layout')

@section('content')
<h1>Course Post</h1>
<a href="{{ route('create_course') }}" class="btn btn-primary mb-3">+ Create New Post</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($posts->count())
    @foreach($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                {{-- รูปภาพทางซ้าย --}}
                <div class="col-md-4 text-center">
                    @if($post->course_image)
                        <img src="{{ asset($post->course_image) }}" 
                             alt="{{ $post->course_name }}" 
                             class="img-fluid rounded" 
                             style="max-width: 150px;">
                    @else
                        <p class="text-muted">No image</p>
                    @endif
                </div>

                {{-- ข้อความทางขวา --}}
                <div class="col-md-8">
                    <h5>{{ $post->course_name }}</h5>
                    <p>{{ Str::limit($post->course_detail, 100) }}</p>
                    <p><strong>Price:</strong> {{ $post->price }}</p>

                    {{-- ปุ่ม --}}
                    <a href="{{ route('admin.showcourse', $post)}}" class="btn btn-secondary btn-sm">View</a>
                    <a href="{{ route('admin.edit_course', $post)}}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.delete_course', $post)}}" 
                          method="POST" 
                          style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else 
    <div class="alert alert-info">ยังไม่มีโพสในระบบ</div>
@endif
@endsection
