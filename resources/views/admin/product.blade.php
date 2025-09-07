{{-- resources/views/admin/product.blade.php --}}
@extends('admin.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('CSS/product.css') }}">

{{-- Flash แจ้งเตือนของใกล้หมด (กันพังถ้าไม่ได้เป็น array/collection) --}}
@php
    $warnings = session('low_stock_warnings');
@endphp
@if (is_iterable($warnings))
  <div class="alert alert-danger">
      <strong>สินค้าใกล้หมด!</strong>
      <ul class="mb-0">
          @foreach ($warnings as $w)
              <li>
                  [#{{ $w['id'] }}] {{ $w['name'] }} —
                  คงเหลือ <strong>{{ $w['qty'] }}</strong> ชิ้น
                  (เกณฑ์แจ้งเตือน {{ $w['threshold'] }} ชิ้น)
              </li>
          @endforeach
      </ul>
  </div>
@endif

<h1 class="mb-4 d-flex align-items-center">
  Product Post
  @php
      // นับจำนวนสินค้าที่ "ใกล้หมด" เพื่อโชว์ Badge รวมบนหัวข้อ
      $lowCount = $posts->filter(fn($p) => (int)$p->quantity <= (int)($p->low_stock_threshold ?? 5))->count();
  @endphp
  @if($lowCount > 0)
    <span class="badge bg-danger ms-3">ใกล้หมด {{ $lowCount }} รายการ</span>
  @endif
</h1>

<a href="{{ route('create')}}" class="btn btn-primary mb-3">+ Create New Products</a>

@if(session('success'))
  <div class="alert alert-success">
      {{ session('success') }}
  </div>
@endif

{{-- เช็คว่าโพสมีรึป่าว --}}
@if($posts->count())
  @foreach($posts as $post)
    @php
        $threshold = (int)($post->low_stock_threshold ?? 5);
        $isLow = (int)$post->quantity <= $threshold;
    @endphp

    <div class="card mb-3 @if($isLow) border-danger @endif">
      <div class="card-body d-flex align-items-start">
          {{-- รูปภาพด้านซ้าย --}}
          @if($post->product_image)
              <img src="{{ asset($post->product_image) }}" alt="{{ $post->product_name }}" class="img-thumbnail me-3" style="width: 150px; height: auto;">
          @else
              <div class="me-3" style="width:150px;height:100px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;border:1px solid #ddd;">
                  <small>No image</small>
              </div>
          @endif

          {{-- ข้อความรายละเอียดด้านขวา --}}
          <div class="flex-grow-1">
              <h5 class="mb-1">{{ $post->product_name }}</h5>

              <p>{{ \Illuminate\Support\Str::limit($post->description, 100) }}</p>

              <div class="mb-2">
                  <span class="me-3">Price: <strong>{{ number_format($post->price, 2) }}</strong></span>

                  {{-- แสดงจำนวนคงเหลือ + ป้ายเตือน --}}
                  <span>
                      quantity:
                      <strong>{{ (int)$post->quantity }}</strong>
                      @if($isLow)
                          <span class="badge bg-danger ms-2">ใกล้หมด (เกณฑ์ {{ $threshold }})</span>
                      @endif
                  </span>
              </div>

              <div class="mb-2">
                  <span class="me-3">material: {{ $post->material }}</span>
                  <span>size: {{ $post->size }}</span>
              </div>

              {{-- ปุ่ม --}}
              <div class="mt-2">
                  <a href="{{ route('admin.show', $post)}}" class="btn btn-secondary">View</a>
                  <a href="{{ route('admin.edit', $post)}}" class="btn btn-warning">Edit</a>
                  <form action="{{ route('admin.delete', $post)}}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">Delete</button>
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
