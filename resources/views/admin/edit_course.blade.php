@extends('admin.layout')

@section('content')
<h1>Edit Course</h1>
<form action="{{ route('admin.update_course', $post->id)}}" method="POST" enctype="multipart/form-data">
    {{-- csrf คือป้องกันการโจมตี --}}
    @csrf
    @method('PUT')
    {{-- ใช้สำหรับการอัปเดตข้อมูล --}}

    <div class="mb-3">
        <label for="title" class="form-label">ชื่อคอร์สเรียน</label>
        <input type="text" class="form-control" id="course_name" name="course_name" value="{{ $post->course_name}}" placeholder="ชื่อคอร์สเรียน" required>
    </div>
    <div class="mb-3">
        <label for="course_detail" class="form-label">รายละเอียดคอร์ส</label>
        <textarea class="form-control" id="course_detail" name="course_detail" rows="5" required>{{$post->course_detail}}</textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">ราคา</label>
        <input type="number" class="form-control" id="price" name="price" required>{{$post->price}}</input>
    </div>
    <div class="mb-3">
    <label for="course_image" class="form-label">รูปภาพสินค้า</label>
    
    {{-- แสดงรูปเก่า ถ้ามี --}}
    @if($post->course_image)
        <div class="mb-2">
            <img src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}" width="200">
        </div>
    @endif

    {{-- ช่องเลือกไฟล์ใหม่ --}}
    <input type="file" class="form-control" id="image" name="image">
</div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.course') }}" class="btn btn-secondary">ยกเลิก</a>
</form>
@endsection

