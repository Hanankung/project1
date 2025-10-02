@extends('admin.layout')

@section('content')
<h1>คลังสินค้า (Archived)</h1>

@if ($posts->count())
    <div class="row g-3">
        @foreach ($posts as $post)
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset($post->product_image) }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->product_name }}</h5>
                        <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                        <form action="{{ route('admin.restore', $post->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">นำกลับมาแสดง</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">ยังไม่มีสินค้าที่เก็บในคลัง</div>
@endif
@endsection
