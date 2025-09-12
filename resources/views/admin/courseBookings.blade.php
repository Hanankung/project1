{{-- resources/views/admin/courseBookings.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2>รายการจองคอร์สเรียนทั้งหมด</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->count())
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>ชื่อผู้จอง</th>
                <th>คอร์ส</th>
                <th>วันที่จอง</th>
                <th class="text-end">จำนวน</th>
                <th class="text-end">ราคา/คน</th>
                <th class="text-end">ราคารวม</th>
                <th class="text-center">สลิป</th>      {{-- ⬅️ เพิ่มคอลัมน์สลิป --}}
                <th class="text-center">สถานะ</th>
                <th class="text-center">จัดการ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings as $booking)
                @php
                    $unit   = (float) ($booking->price ?? 0);
                    $qty    = (int)   ($booking->quantity ?? 0);
                    $total  = isset($booking->total_price) && $booking->total_price !== null ? (float)$booking->total_price : $unit * $qty;

                    $hasSlip = !empty($booking->payment_slip);
                    $url     = $hasSlip ? \Illuminate\Support\Facades\Storage::url($booking->payment_slip) : null;
                    $ext     = $hasSlip ? strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) : null;
                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                @endphp
                <tr>
                    <td>{{ $booking->name }} {{ $booking->lastname }}</td>
                    <td>{{ $booking->course_name }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td class="text-end">{{ $qty }}</td>
                    <td class="text-end">{{ number_format($unit, 2) }} บาท</td>
                    <td class="text-end fw-semibold">{{ number_format($total, 2) }} บาท</td>

                    {{-- สลิป --}}
                    <td class="text-center">
                        @if($hasSlip)
                            @if($isImage)
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#slip-{{ $booking->id }}">
                                    ดูสลิป
                                </button>

                                {{-- Modal แสดงสลิปรูปภาพ --}}
                                <div class="modal fade" id="slip-{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">สลิปการชำระเงิน #{{ $booking->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ $url }}" class="img-fluid rounded w-100" alt="payment slip">
                                                <div class="mt-2">
                                                    <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                                                        เปิดไฟล์ต้นฉบับ
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a class="btn btn-outline-primary btn-sm" href="{{ $url }}" target="_blank" rel="noopener">
                                    เปิดสลิป (PDF)
                                </a>
                            @endif
                        @else
                            <span class="badge bg-secondary">ไม่มีสลิป</span>
                        @endif
                    </td>

                    {{-- สถานะ --}}
                    <td class="text-center">
                        <span class="badge
                            @if($booking->status == 'รอดำเนินการ') bg-warning text-dark
                            @elseif($booking->status == 'อนุมัติ') bg-success
                            @elseif($booking->status == 'ไม่อนุมัติ') bg-danger
                            @else bg-secondary @endif">
                            {{ $booking->status }}
                        </span>
                    </td>

                    {{-- จัดการ: อนุมัติ/ไม่อนุมัติ เฉพาะเมื่อ "รอดำเนินการ" และ "มีสลิป" --}}
                    <td class="text-center">
                        {{-- ปุ่มรายละเอียด (เปิดโมดัล) --}}
                        <button type="button"
                                class="btn btn-outline-primary btn-sm mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#booking-{{ $booking->id }}">
                            รายละเอียด
                        </button>

                        @if($booking->status === 'รอดำเนินการ' && $hasSlip)
                            <form action="{{ route('admin.course.booking.approve', $booking->id) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm">อนุมัติ</button>
                            </form>
                            <form action="{{ route('admin.course.booking.reject', $booking->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันไม่อนุมัติรายการนี้?');">
                                @csrf @method('PATCH')
                                <button class="btn btn-danger btn-sm">ไม่อนุมัติ</button>
                            </form>
                        @elseif($booking->status === 'รอดำเนินการ' && !$hasSlip)
                            <span class="text-muted small">รอสลิป</span>
                        @else
                            <em>-</em>
                        @endif
                    </td>
                </tr>

                {{-- Modal รายละเอียด --}}
                <div class="modal fade" id="booking-{{ $booking->id }}" tabindex="-1"
                     aria-labelledby="bookingLabel-{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="bookingLabel-{{ $booking->id }}" class="modal-title">
                                    รายละเอียดการจอง #{{ $booking->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <dl class="row">
                                    <dt class="col-sm-4">ผู้จอง</dt>
                                    <dd class="col-sm-8">{{ $booking->name }} {{ $booking->lastname }}</dd>

                                    <dt class="col-sm-4">อีเมล</dt>
                                    <dd class="col-sm-8">
                                        @if($booking->email)
                                            <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
                                        @else - @endif
                                    </dd>

                                    <dt class="col-sm-4">โทร</dt>
                                    <dd class="col-sm-8">
                                        @if($booking->phone)
                                            <a href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a>
                                        @else - @endif
                                    </dd>

                                    <dt class="col-sm-4">คอร์ส</dt>
                                    <dd class="col-sm-8">{{ $booking->course_name }}</dd>

                                    <dt class="col-sm-4">วันที่จอง</dt>
                                    <dd class="col-sm-8">{{ $booking->booking_date }}</dd>

                                    <dt class="col-sm-4">จำนวน</dt>
                                    <dd class="col-sm-8">{{ $qty }} คน</dd>

                                    <dt class="col-sm-4">ราคา/คน</dt>
                                    <dd class="col-sm-8">{{ number_format($unit, 2) }} บาท</dd>

                                    <dt class="col-sm-4">ราคารวม</dt>
                                    <dd class="col-sm-8 fw-semibold">{{ number_format($total, 2) }} บาท</dd>

                                    <dt class="col-sm-4">สถานะ</dt>
                                    <dd class="col-sm-8">{{ $booking->status }}</dd>

                                    @if(!empty($booking->payment_slip))
                                        <dt class="col-sm-4">สลิป</dt>
                                        <dd class="col-sm-8">
                                            @if($isImage)
                                                <img src="{{ $url }}" class="img-fluid rounded border" alt="payment slip">
                                            @else
                                                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                                    เปิดสลิป (PDF)
                                                </a>
                                            @endif
                                        </dd>
                                    @endif

                                    @if(isset($booking->created_at))
                                        <dt class="col-sm-4">สร้างเมื่อ</dt>
                                        <dd class="col-sm-8">{{ $booking->created_at }}</dd>
                                    @endif
                                </dl>
                            </div>
                            <div class="modal-footer">
                                @if($booking->status === 'รอดำเนินการ' && $hasSlip)
                                    <form action="{{ route('admin.course.booking.approve', $booking->id) }}"
                                          method="POST" class="me-auto">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-success">อนุมัติ</button>
                                    </form>
                                    <form action="{{ route('admin.course.booking.reject', $booking->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('ยืนยันไม่อนุมัติรายการนี้?');">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-danger">ไม่อนุมัติ</button>
                                    </form>
                                @elseif($booking->status === 'รอดำเนินการ' && !$hasSlip)
                                    <span class="text-muted small me-auto">รอสลิป</span>
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">ยังไม่มีการจองคอร์ส</div>
    @endif
</div>

{{-- ถ้า layout ยังไม่ได้ใส่ Bootstrap JS ให้ใส่บรรทัดนี้ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
