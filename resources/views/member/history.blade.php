@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    @php
        $courseTypes = __('messages.course_type_options');
        $fabricTypes = __('messages.fabric_type_options');

        // แปลงค่าจาก DB -> โค้ดกลาง
        $normalizeStatus = function ($status) {
            $s = trim($status);
            // ถ้า DB เก็บเป็นโค้ดอยู่แล้ว ก็ใช้ได้เลย
            if (in_array($s, ['pending', 'approved', 'rejected', 'cancelled'], true)) {
                return $s;
            }

            // รองรับคำไทย/คำพ้อง และช่องว่างเกิน
            $s = trim($s);
            return match ($s) {
                'รอดำเนินการ', 'รออนุมัติ', 'กำลังดำเนินการ' => 'pending',
                'อนุมัติ', 'ผ่าน' => 'approved',
                'ไม่อนุมัติ', 'ปฏิเสธ' => 'rejected',
                'ยกเลิก' => 'cancelled',
                default => 'pending', // หรือ return $s;
            };
        };
    @endphp

    <h1>{{ __('messages.booking_history_title') }}</h1>
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            // ดึงออปชันจากไฟล์ภาษา (array)
            $courseTypes = __('messages.course_type_options');
            $fabricTypes = __('messages.fabric_type_options');
            $statusOptions = __('messages.status_options');

            // ฟังก์ชันช่วย map สถานะที่อาจเก็บ "คำไทย" ใน DB ให้เป็น key กลาง
            $normalizeStatus = function ($status) {
                return match ($status) {
                    'รอดำเนินการ' => 'pending',
                    'อนุมัติ' => 'approved',
                    'ไม่อนุมัติ' => 'rejected',
                    default => $status, // กรณีเก็บเป็น code อยู่แล้ว
                };
            };
        @endphp

        @if ($posts->count())
            @foreach ($posts as $post)
                @php
                    $statusKey = $normalizeStatus($post->status);
                    $statusLabel = $statusOptions[$statusKey] ?? $post->status;
                    $courseTypeLabel = $courseTypes[$post->course_type] ?? $post->course_type;
                    $fabricTypeLabel = $fabricTypes[$post->fabric_type] ?? $post->fabric_type;
                @endphp

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $post->course_name }}</h5>
                        <p><strong>{{ __('messages.first_name') }}:</strong> {{ $post->name }}</p>
                        <p><strong>{{ __('messages.last_name') }}:</strong> {{ $post->lastname }}</p>
                        <p><strong>{{ __('messages.Phone') }}:</strong> {{ $post->phone }}</p>
                        <p><strong>{{ __('messages.email') }}:</strong> {{ $post->email }}</p>
                        <p><strong>{{ __('messages.people_qty') }}:</strong> {{ $post->quantity }}</p>
                        <p><strong>{{ __('messages.price') }}:</strong> {{ $post->price }} {{ __('messages.baht') }}</p>
                        <p><strong>{{ __('messages.booking_date') }}:</strong> {{ $post->booking_date }}</p>
                        <p><strong>{{ __('messages.course_type') }}:</strong> {{ $post->course_type }}</p>
                        <p><strong>{{ __('messages.fabric_type') }}:</strong> {{ $post->fabric_type }}</p>
                        <p><strong>{{ __('messages.fabric_length_m') }}:</strong> {{ $post->fabric_length }}</p>
                        @php
                            $statusKey = $normalizeStatus($post->status);
                        @endphp
                        <p><strong>{{ __('messages.status') }}:</strong> {{ __('messages.status_options.' . $statusKey) }}
                        </p>
                        {{-- แสดงปุ่มยกเลิก เฉพาะเมื่อยังรอดำเนินการ --}}
                        @if ($statusKey === 'pending')
                            <form action="{{ route('member.course.booking.cancel', $post->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('messages.cancel_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    {{ __('messages.cancel_booking') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">{{ __('messages.no_bookings') }}</div>
        @endif
        <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
@endsection
