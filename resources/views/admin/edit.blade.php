@extends('admin.layout')

@section('content')
<h1>Edit Post</h1>

<form action="{{ route('admin.update', $post->id)}}" method="POST" enctype="multipart/form-data">
    {{-- csrf คือป้องกันการโจมตี --}}
    @csrf
    @method('PUT') 
    {{-- ใช้สำหรับการอัปเดตข้อมูล --}}
    
    {{-- แสดงข้อความแจ้งเตือนถ้ามี --}}
    <div class="mb-3">
        <label for="title" class="form-label">ชื่อสินค้า</label>
        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $post->product_name}}" placeholder="ชื่อสินค้า">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">รายละเอียดสินค้า</label>
        <textarea class="form-control" id="description" name="description" rows="5" required>{{$post->description}}</textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">ราคา</label>
        <input type="number" class="form-control" id="price" name="price" required>{{$post->price}}</input>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">จำนวนสินค้า</label>
        <input type="number" class="form-control" id="quantity" name="quantity" required>{{$post->quantity}}</input>
    </div>
    <div class="mb-3">
    <label for="image" class="form-label">รูปภาพสินค้า</label>
    
    {{-- แสดงรูปเก่า ถ้ามี --}}
    @if($post->product_image)
        <div class="mb-2">
            <img src="{{ asset($post->product_image) }}" alt="{{ $post->product_name }}" width="200">
        </div>
    @endif

    {{-- ช่องเลือกไฟล์ใหม่ --}}
    <input type="file" class="form-control" id="image" name="image">
</div>
    <div class="mb-3">
        <label for="material" class="form-label">วัสดุ</label>
        <textarea class="form-control" id="material" name="material" rows="3" required>{{$post->material}}</textarea>
    </div>
    <div class="mb-3">
        <label for="size" class="form-label">ขนาด</label>
        <textarea class="form-control" id="size" name="size" rows="3" required>{{$post->size}}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">อัปเดทสินค้า</button>
    <a href="{{ route('admin.product') }}" class="btn btn-secondary">ยกเลิก</a>
</form>
@endsection