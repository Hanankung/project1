@extends('admin.layout')

@section('content')
<form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
    {{-- csrf คือป้องกันการโจมตี --}}
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">ชื่อสินค้า</label>
        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="ชื่อสินค้า">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">รายละเอียดสินค้า</label>
        <textarea class="form-control" id="description" name="description" rows="5" placeholder="รายละเอียดสินค้า"></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">ราคา</label>
        <input type="number" class="form-control" id="price" name="price" placeholder="ราคา">
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">จำนวนสินค้า</label>
        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="จำนวนสินค้า">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">รูปภาพสินค้า</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <div class="mb-3">
        <label for="material" class="form-label">วัสดุ</label>
        <textarea class="form-control" id="material" name="material" rows="3" placeholder="วัสดุ"></textarea>
    </div>
    <div class="mb-3">
        <label for="size" class="form-label">ขนาด</label>
        <textarea class="form-control" id="size" name="size" rows="3" placeholder="ขนาด"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">บันทึกสินค้า</button>
    <a href="{{ route('admin.product') }}" class="btn btn-secondary">ยกเลิก</a>
</form>

@endsection