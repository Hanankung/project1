@extends('member.layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    {{-- สร้างฟอร์มสมัครคอร์ส --}}
    <div class="container mt-4">
        <h2 class="mb-4">ฟอร์มจองคอร์ส</h2>

        <form action="{{ route('member.course.booking.store') }}" method="POST">
            @csrf

            {{-- ข้อมูลผู้จอง --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="name" name="name" maxlength="20" required>
                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" maxlength="20" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control" id="phone" name="phone" maxlength="10" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="50" required>
                </div>
            </div>

            {{-- รายละเอียดการจอง --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="quantity" class="form-label">จำนวนคน</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">ราคา (บาท)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="col-md-4">
                    <label for="booking_date" class="form-label">วันที่จอง</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                </div>
            </div>

            {{-- รายละเอียดคอร์ส --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="course_name" class="form-label">ชื่อคอร์ส</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ประเภทคอร์ส</label>
                    <select name="course_type" class="form-select">
                        <option value="">-- เลือกประเภทคอร์สเรียน --</option>
                        <option value="แบบสีผสม">แบบสีผสม</option>
                        <option value="แบบทุบ">แบบทุบ</option>
                        <option value="แบบสีสนิม">แบบสีสนิม</option>
                    </select>
                </div>
            </div>
            {{-- รายละเอียดผ้า --}}
            <div class="mb-3">
                <label class="form-label">ชนิดของผ้า</label>
                <select name="fabric_type" class="form-select">
                    <option value="">-- เลือกประเภทของผ้า --</option>
                    <option value="ผ้าฝ้าย">ผ้าฝ้าย</option>
                    <option value="ผ้าไหม">ผ้าไหม</option>
                    <option value="ผ้าลินิน">ผ้าลินิน</option>
                    <option value="ผ้าเรยอน">ผ้าเรยอน</option>
                    <option value="ผ้าพันคอ">ผ้าพันคอ</option>
                    <option value="เสื้อยืด">เสื้อยืด</option>
                </select>
            </div>
            
            {{-- รายละเอียดผ้า --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fabric_length" class="form-label">ความยาวของผ้า (เมตร)</label>
                    <input type="number" step="0.1" class="form-control" id="fabric_length" name="fabric_length">
                </div>
            </div>

            {{-- สถานะ (ให้แอดมินใช้ แต่เผื่อใส่ไว้สำหรับการทดสอบ) --}}
            {{-- <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-select" id="status" name="status">
                    <option value="pending" selected>รอดำเนินการ</option>
                    <option value="approved">อนุมัติ</option>
                    <option value="rejected">ไม่อนุมัติ</option>
                </select>
            </div> --}}

            <button type="submit" class="btn btn-primary">บันทึกการจอง</button>
            <a href="{{ route('member.courses') }}" class="btn btn-secondary mt-3">ย้อนกลับ</a>
        </form>
        @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    </div>
    
@endsection
