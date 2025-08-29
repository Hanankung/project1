@extends('admin.layout')

@section('content')
    <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
        {{-- csrf คือป้องกันการโจมตี --}}
        @csrf
        {{-- ชื่อสินค้า --}}
        <div class="mb-3">
            <label for="title" class="form-label">ชื่อสินค้า</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="ชื่อสินค้า">
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อสินค้า (English)</label>
            <input type="text" class="form-control" name="product_name_ENG" placeholder="Product name in English">
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อสินค้า (Malay)</label>
            <input type="text" class="form-control" name="product_name_MS" placeholder="Product name in Malay">
        </div>
        {{-- รายละเอียดสินค้า --}}
        <div class="mb-3">
            <label for="description" class="form-label">รายละเอียดสินค้า</label>
            <textarea class="form-control" id="description" name="description" rows="5" placeholder="รายละเอียดสินค้า"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">รายละเอียด (English)</label>
            <textarea class="form-control" name="description_ENG" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">รายละเอียด (Malay)</label>
            <textarea class="form-control" name="description_MS" rows="3"></textarea>
        </div>
        {{-- ราคา --}}
        <div class="mb-3">
            <label for="price" class="form-label">ราคา</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="ราคา">
        </div>
        {{-- จำนวนสินค้า --}}
        <div class="mb-3">
            <label for="quantity" class="form-label">จำนวนสินค้า</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="จำนวนสินค้า">
        </div>
        {{-- รูปภาพ --}}
        <div class="mb-3">
            <label for="image" class="form-label">รูปภาพสินค้า</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        {{-- วัสดุ --}}
        <div class="mb-3">
            <label for="material" class="form-label">วัสดุ</label>
            <textarea class="form-control" id="material" name="material" rows="3" placeholder="วัสดุ"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">วัสดุ (English)</label>
            <input type="text" class="form-control" name="material_ENG" placeholder="Material in English">
        </div>
        <div class="mb-3">
            <label class="form-label">วัสดุ (Malay)</label>
            <input type="text" class="form-control" name="material_MS" placeholder="Material in Malay">
        </div>
        <div class="mb-3">
            <label for="size" class="form-label">ขนาด</label>
            <textarea class="form-control" id="size" name="size" rows="3" placeholder="ขนาด"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกสินค้า</button>
        <a href="{{ route('admin.product') }}" class="btn btn-secondary">ยกเลิก</a>
    </form>
@endsection
