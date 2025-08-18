@extends('member.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<h1>ประวัติการจองคอร์สเรียน</h1>
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($posts->count())
        @foreach($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $post->course_name }}</h5>
                <p><strong>ชื่อ:</strong> {{ $post->name }}</p>
                <p><strong>นามสกุล:</strong> {{ $post->lastname }}</p>
                <p><strong>เบอร์โทรศัพท์:</strong> {{ $post->phone }}</p>
                <p><strong>อีเมล:</strong> {{ $post->email }}</p>
                <p><strong>จำนวนคน:</strong> {{ $post->quantity }}</p>
                <p><strong>ราคา:</strong> {{ $post->price }} บาท</p>
                <p><strong>วันที่จอง:</strong> {{ $post->booking_date }}</p>
                <p><strong>ประเภทคอร์ส:</strong> {{ $post->course_type }}</p>
                <p><strong>ประเภทผ้า:</strong> {{ $post->fabric_type }}</p>
                <p><strong>ความยาวผ้า:</strong> {{ $post->fabric_length }}</p>
            </div>
        </div>
        @endforeach
    @else 
        <div class="alert alert-info">ยังไม่มีการจองคอร์สเรียน</div>
    @endif
    <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> ย้อนกลับ 
    </a>
</div>
@endsection