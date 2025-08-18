@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2>รายการจองคอร์สเรียนทั้งหมด</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อผู้จอง</th>
                    <th>คอร์ส</th>
                    <th>วันที่จอง</th>
                    <th>จำนวน</th>
                    <th>ราคา</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->name }} {{ $booking->lastname }}</td>
                    <td>{{ $booking->course_name }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td>{{ $booking->quantity }}</td>
                    <td>{{ $booking->price }} บาท</td>
                    <td>
                        <span class="badge 
                            @if($booking->status == 'รอดำเนินการ') bg-warning 
                            @elseif($booking->status == 'อนุมัติ') bg-success 
                            @else bg-danger @endif">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td>
                        @if($booking->status === 'รอดำเนินการ')
                            <form action="{{ route('admin.course.booking.approve', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-success btn-sm">อนุมัติ</button>
                            </form>
                            <form action="{{ route('admin.course.booking.reject', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-danger btn-sm">ไม่อนุมัติ</button>
                            </form>
                        @else
                            <em>-</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">ยังไม่มีการจองคอร์ส</div>
    @endif
</div>
@endsection
