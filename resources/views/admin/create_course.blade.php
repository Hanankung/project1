@extends('admin.layout')

@section('content')
<form action="{{ route('store_course')}}" method="POST" enctype="multipart/form-data">
    {{-- csrf คือป้องกันการโจมตี --}}
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">ชื่อคอร์สเรียน</label>
        <input type="text" class="form-control" id="course_name" name="course_name" placeholder="ชื่อคอร์สเรียน" required>
    </div>
    <div class="mb-3">
        <label for="course_detail" class="form-label">รายละเอียดคอร์ส</label>
        <textarea class="form-control" id="course_detail" name="course_detail" rows="5" placeholder="รายละเอียดคอร์สเรียน"></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">ราคา</label>
        <input type="number" class="form-control" id="price" name="price" placeholder="ราคา">
    </div>
    <div class="mb-3">
        <label for="course_image" class="form-label">รูปภาพสินค้า</label>
        <input type="file" class="form-control" id="course_image" name="course_image">
    </div>
    <button type="submit" class="btn btn-primary">บันทึกสินค้า</button>
    <a href="{{ route('admin.course') }}" class="btn btn-secondary">ยกเลิก</a>
</form>
@endsection