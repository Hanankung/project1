@extends('admin.layout')

@section('content')
<h1>Edit Course</h1>

<form action="{{ route('admin.update_course', $post->id) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ชื่อคอร์สเรียน --}}
    <div class="mb-3">
        <label for="course_name" class="form-label">ชื่อคอร์สเรียน</label>
        <input type="text" class="form-control" id="course_name" name="course_name"
               value="{{ $post->course_name }}" required>
    </div>

    <div class="mb-3">
        <label for="course_name_ENG" class="form-label">ชื่อคอร์สเรียน (English)</label>
        <input type="text" class="form-control" id="course_name_ENG" name="course_name_ENG"
               value="{{ $post->course_name_ENG }}">
    </div>

    <div class="mb-3">
        <label for="course_name_MS" class="form-label">ชื่อคอร์สเรียน (Malay)</label>
        <input type="text" class="form-control" id="course_name_MS" name="course_name_MS"
               value="{{ $post->course_name_MS }}">
    </div>

    {{-- รายละเอียด --}}
    <div class="mb-3">
        <label for="course_detail" class="form-label">รายละเอียดคอร์ส</label>
        <textarea class="form-control" id="course_detail" name="course_detail" rows="5" required>{{ $post->course_detail }}</textarea>
    </div>

    <div class="mb-3">
        <label for="course_detail_ENG" class="form-label">รายละเอียดคอร์ส (English)</label>
        <textarea class="form-control" id="course_detail_ENG" name="course_detail_ENG" rows="5">{{ $post->course_detail_ENG }}</textarea>
    </div>

    <div class="mb-3">
        <label for="course_detail_MS" class="form-label">รายละเอียดคอร์ส (Malay)</label>
        <textarea class="form-control" id="course_detail_MS" name="course_detail_MS" rows="5">{{ $post->course_detail_MS }}</textarea>
    </div>

    {{-- ราคา --}}
    <div class="mb-3">
        <label for="price" class="form-label">ราคา</label>
        <input type="number" class="form-control" id="price" name="price"
               value="{{ $post->price }}" required>
    </div>

    {{-- ภาพ --}}
    <div class="mb-3">
        <label for="course_image" class="form-label">รูปภาพคอร์ส</label>

        @if($post->course_image)
            <div class="mb-2">
                <img src="{{ asset($post->course_image) }}" alt="{{ $post->course_name }}" width="200">
            </div>
        @endif

        <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
        <small class="text-muted">อัปโหลดเฉพาะเมื่ออยากเปลี่ยนรูปใหม่</small>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.course') }}" class="btn btn-secondary">ยกเลิก</a>
</form>
@endsection
