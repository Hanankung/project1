@extends('admin.layout')

@section('content')
    <form action="{{ route('store_course') }}" method="POST" enctype="multipart/form-data">
        {{-- csrf คือป้องกันการโจมตี --}}
        @csrf
        {{-- ชื่อคอร์สเรียน --}}
        <div class="mb-3">
            <label for="title" class="form-label">ชื่อคอร์สเรียน</label>
            <input type="text" class="form-control" id="course_name" name="course_name" placeholder="ชื่อคอร์สเรียน"
                required>
        </div>
        <div class="mb-3">
            <label for="course_name_ENG" class="form-label">ชื่อคอร์สเรียน (English)</label>
            <input type="text" class="form-control" id="course_name_ENG" name="course_name_ENG"
                placeholder="Course name in English">
        </div>
        <div class="mb-3">
            <label for="course_name_MS" class="form-label">ชื่อคอร์สเรียน (Malay)</label>
            <input type="text" class="form-control" id="course_name_MS" name="course_name_MS"
                placeholder="Course name in Malay">
        </div>
        {{-- รายละเอียด --}}
        <div class="mb-3">
            <label for="course_detail" class="form-label">รายละเอียดคอร์ส</label>
            <textarea class="form-control" id="course_detail" name="course_detail" rows="5"
                placeholder="รายละเอียดคอร์สเรียน"></textarea>
        </div>
        <div class="mb-3">
            <label for="course_detail_ENG" class="form-label">รายละเอียดคอร์ส (English)</label>
            <textarea class="form-control" id="course_detail_ENG" name="course_detail_ENG" rows="5"
                placeholder="Course details in English"></textarea>
        </div>
        <div class="mb-3">
            <label for="course_detail_MS" class="form-label ">รายละเอียดคอร์ส (Malay)</label>
            <textarea class="form-control" id="course_detail_MS" name="course_detail_MS" rows="5"
                placeholder="Course details in Malay"></textarea>
        </div>
        {{-- ราคา --}}
        <div class="mb-3">
            <label for="price" class="form-label">ราคา</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="ราคา">
        </div>
        <div class="mb-3">
            <label for="course_image" class="form-label">รูปภาพคอร์ส</label>
            <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">บันทึกสินค้า</button>
        <a href="{{ route('admin.course') }}" class="btn btn-secondary">ยกเลิก</a>
    </form>
@endsection
