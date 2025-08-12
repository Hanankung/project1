@extends('admin.layout')

@section('content')
<h1>Course Post</h1>
<a href="{{ route('create_course') }}" class="btn btn-primary mb-3">+ Create New Post</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- เช็คว่าโพสมีรึป่าว --}}
@if($posts->count())
    @foreach($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $post->course_name }}</h5>
            {{-- รายละเอียดข้อความเยอะเกินไป กำหนดลิมิตที่แสดง --}}
            <p>{{ Str::limit($post->course_detail, 100) }}</p>
            <p>Price: {{ $post->price }}</p>

            @if($post->course_image)
                <img src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}" width="200">
            @else
                <p>No image</p>
            @endif

            {{-- ปุ่มดูรายละเอียด แก้ไข และลบโพส --}}
            <a href="{{ route('admin.showcourse', $post)}}" class="btn btn-secondary">View</a>
            <a href="{{ route('admin.edit_course', $post)}}" class="btn btn-warning">Edit</a>
            <form action="{{ route('admin.delete_course', $post)}}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
@else 
    <div class="alert alert-info">ยังไม่มีโพสในระบบ</div>
@endif
@endsection
