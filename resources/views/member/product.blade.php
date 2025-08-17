@extends('member.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/product1.css') }}">
</head>
{{-- row-cols-1 row-cols-md-3 g-4 → แสดง 1 column บนมือถือ, 3 columns บน desktop --}}
{{-- g-4 คือ spacing ระหว่าง card --}}
<div class="row row-cols-1 row-cols-md-6 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            @if($product->product_image)
            <img src="{{ asset($product->product_image) }}" class="card-img-top" alt="{{ $product->product_name }}">
            @else
            <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="ไม่มีรูปภาพ">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->product_name }}</h5>
                {{-- <p class="card-text">{{ Str::limit($product->description, 100) }}</p> --}}
                <p class="mb-1"><strong>ราคา:</strong> {{ $product->price }} บาท</p>
                {{-- <p class="mb-1"><strong>จำนวน:</strong> {{ $product->quantity }}</p>
                <p class="mb-1"><strong>ขนาด:</strong> {{ $product->size }}</p> --}}
                {{-- <a href="{{ route('admin.show', $post)}}" class="btn btn-secondary">View</a> --}}
                <a href="#" class="btn btn-primary btn-sm mt-2 w-100">รายละเอียด</a>
                <a href="#" class="btn btn-primary btn-sm mt-2 w-100">สั่งซื้อ</a>
                {{-- <a href="{{ route('create')}}" class="btn btn-primary mb-3">+ Create New Post</a> --}}
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">ยังไม่มีโพสในระบบ</div>
    @endforelse
</div>
@endsection