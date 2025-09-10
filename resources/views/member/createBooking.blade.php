@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    {{-- สร้างฟอร์มสมัครคอร์ส --}}
    <div class="container mt-4">
        <h2 class="mb-4">{{ __('messages.booking_form_title') }}</h2>
        {{-- แสดง error validation แบบรวม --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php $isLocked = !empty($prefill['courseId']); @endphp
        @if ($isLocked)
            <input type="hidden" name="course_id" value="{{ $prefill['courseId'] }}">
        @endif


        <form action="{{ route('member.course.booking.store') }}" method="POST">
            @csrf

            {{-- ข้อมูลผู้จอง --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">{{ __('messages.first_name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" maxlength="20" required>
                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label">{{ __('messages.last_name') }}</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" maxlength="20" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">{{ __('messages.Phone') }}</label>
                    <input type="text" class="form-control" id="phone" name="phone" maxlength="10" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="50" required>
                </div>
            </div>

            {{-- รายละเอียดการจอง --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="quantity" class="form-label">{{ __('messages.people_qty') }}</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">{{ __('messages.price_thb') }}</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="col-md-4">
                    <label for="booking_date" class="form-label">{{ __('messages.booking_date') }}</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                </div>
            </div>

            {{-- รายละเอียดคอร์ส --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="course_name" class="form-label">{{ __('messages.course_name') }}</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" maxlength="50"
                        value="{{ old('course_name', $prefill['courseName']) }}" {{ $isLocked ? 'readonly' : '' }}
                        required>
                </div>
            </div>
           
            <button type="submit" class="btn btn-primary">{{ __('messages.save_booking') }}</button>
            <a href="{{ route('member.courses') }}" class="btn btn-secondary mt-3">{{ __('messages.back') }}</a>
        </form>
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>

@endsection
