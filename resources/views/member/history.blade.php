{{-- resources/views/member/history.blade.php --}}
@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .history-hero {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 40%, #e8f5ff 100%);
                border-bottom: 1px solid #eef2f7;
                padding: 26px 0 14px;
                margin-bottom: 18px
            }

            .booking-card {
                border: 0;
                border-radius: 16px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .06)
            }

            .section-title {
                font-weight: 700;
                font-size: 1.05rem
            }

            .divider {
                height: 1px;
                background: #eef2f7;
                margin: 10px 0 14px
            }

            .kv {
                display: flex;
                justify-content: space-between;
                gap: 12px
            }

            .kv span:first-child {
                color: #6c757d
            }

            .price-strong {
                font-weight: 800
            }

            .thumb-slip {
                max-width: 220px;
                border: 1px solid #e9ecef;
                border-radius: 10px
            }
        </style>
    </head>

    {{-- Hero --}}
    <div class="history-hero">
        <div class="container">
            <h2 class="mb-1">{{ __('messages.booking_history_title') }}</h2>
            <div class="text-muted">
                <i class="bi bi-clock-history me-1"></i>{{ __('messages.summary_estimate') ?? 'ยอดชำระโดยประมาณ' }}
                {{ __('messages.show_both_thb_myr') }}
            </div>
        </div>
    </div>

    @php
        $rate = (float) config('currency.rates.THB_MYR', 0.13); // เรต THB -> MYR

        // map สถานะ
        $normalizeStatus = function ($status) {
            $s = trim($status);
            if (in_array($s, ['pending', 'approved', 'rejected', 'cancelled'], true)) {
                return $s;
            }
            return match ($s) {
                'รอดำเนินการ', 'รออนุมัติ', 'กำลังดำเนินการ' => 'pending',
                'อนุมัติ', 'ผ่าน' => 'approved',
                'ไม่อนุมัติ', 'ปฏิเสธ' => 'rejected',
                'ยกเลิก' => 'cancelled',
                default => 'pending',
            };
        };
    @endphp

    <div class="container">
        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        @if ($posts->count())
            @foreach ($posts as $post)
                @php
                    $unit = (float) ($post->price ?? 0); // THB/คน
                    $qty = (int) ($post->quantity ?? 0);
                    $total =
                        isset($post->total_price) && $post->total_price !== null
                            ? (float) $post->total_price
                            : $unit * $qty;

                    $unitMyr = $unit * $rate;
                    $totalMyr = $total * $rate;

                    $statusKey = $normalizeStatus($post->status);
                    $badgeClass = match ($statusKey) {
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'cancelled' => 'bg-secondary',
                        default => 'bg-secondary',
                    };

                    // สลิป
                    $hasSlip = !empty($post->payment_slip ?? null);
                    $url = $hasSlip ? \Illuminate\Support\Facades\Storage::url($post->payment_slip) : null;
                    $ext = $hasSlip ? strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) : null;
                    $isImage = $hasSlip ? in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) : false;
                @endphp

                <div class="card booking-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="fw-bold">{{ $post->course_name }}</div>
                        <span class="badge {{ $badgeClass }}">
                            {{ __('messages.status_options.' . $statusKey) }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- ซ้าย: ข้อมูลผู้จอง + สลิป --}}
                            <div class="col-md-7">
                                <div class="section-title">
                                    <i
                                        class="bi bi-person-circle me-1"></i>{{ __('messages.section_customer') ?? 'ข้อมูลผู้จอง' }}
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-1"><strong>{{ __('messages.first_name') }}:</strong>
                                        {{ $post->name }}</div>
                                    <div class="col-6 mb-1"><strong>{{ __('messages.last_name') }}:</strong>
                                        {{ $post->lastname }}</div>
                                    <div class="col-6 mb-1"><strong>{{ __('messages.Phone') }}:</strong>
                                        {{ $post->phone }}</div>
                                    <div class="col-6 mb-1"><strong>{{ __('messages.email') }}:</strong>
                                        {{ $post->email }}</div>
                                </div>

                                <div class="divider"></div>

                                {{-- สลิปการชำระเงิน --}}
                                <div class="mb-1">
                                    <strong>{{ __('messages.payment_slip_label') ?? 'สลิปการชำระเงิน' }}:</strong></div>
                                @if ($hasSlip)
                                    @if ($isImage)
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#slip-{{ $post->id }}">
                                            {{ __('messages.view_slip') ?? 'ดูสลิป' }}
                                        </button>
                                        {{-- Modal --}}
                                        <div class="modal fade" id="slip-{{ $post->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            {{ __('messages.payment_slip_label') ?? 'สลิปการชำระเงิน' }}
                                                            #{{ $post->id }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{ $url }}" class="img-fluid rounded w-100"
                                                            alt="payment slip">
                                                        <div class="mt-2">
                                                            <a href="{{ $url }}" target="_blank" rel="noopener"
                                                                class="btn btn-outline-secondary btn-sm">
                                                                {{ __('messages.open_original') ?? 'เปิดไฟล์ต้นฉบับ' }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ $url }}" target="_blank" rel="noopener"
                                            class="btn btn-outline-primary btn-sm">
                                            {{ __('messages.open_slip_pdf') ?? 'เปิดสลิป (PDF)' }}
                                        </a>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.no_slip') ?? 'ไม่มีสลิป' }}</span>
                                @endif
                            </div>

                            {{-- ขวา: สรุปราคา --}}
                            <div class="col-md-5">
                                <div class="section-title"><i
                                        class="bi bi-receipt-cutoff me-1"></i>{{ __('messages.summary_title') ?? 'สรุปรายการจอง' }}
                                </div>

                                <div class="kv"><span>{{ __('messages.people_qty') }}:</span><span
                                        class="fw-semibold">{{ $qty }}</span></div>
                                <div class="divider"></div>
                                <div class="kv">
                                    <span>{{ __('messages.price_per_person') ?? 'ราคา/คน (บาท)' }}:</span><span>{{ number_format($unit, 2) }}
                                        {{ __('messages.baht') }}</span></div>
                                <div class="kv">
                                    <span>{{ __('messages.total_price_thb') ?? 'ราคารวม (บาท)' }}:</span><span
                                        class="price-strong">{{ number_format($total, 2) }}
                                        {{ __('messages.baht') }}</span></div>
                                <div class="divider"></div>
                                <div class="kv">
                                    <span>{{ __('messages.price_per_person_myr') ?? 'ราคา/คน (MYR)' }}:</span><span>{{ number_format($unitMyr, 2) }}
                                        MYR</span></div>
                                <div class="kv">
                                    <span>{{ __('messages.total_price_myr') ?? 'ราคารวม (MYR)' }}:</span><span
                                        class="price-strong">{{ number_format($totalMyr, 2) }} MYR</span></div>
                                <div class="text-muted small mt-1">
                                    <i
                                        class="bi bi-info-circle me-1"></i>{{ __('messages.summary_exchange_note') ?? 'คำนวณจากอัตราแลกเปลี่ยนล่าสุดที่กำหนดในระบบ' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>{{ __('messages.booking_date') }}:
                            {{ \Carbon\Carbon::parse($post->booking_date)->format('Y-m-d') }}
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>{{ __('messages.booking_created_at') }}:
                            {{ optional($post->created_at)->format('Y-m-d H:i') }}
                        </div>

                        {{-- ✅ ใช้ SweetAlert2 ยืนยัน ไม่ใช้ confirm() --}}
                        @if ($statusKey === 'pending')
                            <form action="{{ route('member.course.booking.cancel', $post->id) }}" method="POST"
                                class="d-inline cancel-booking-form" data-course-name="{{ $post->course_name }}"
                                data-booking-id="{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm cancel-booking-btn">
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

    {{-- ========= SCRIPTS ========= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ยืนยันยกเลิกการจองคอร์สด้วย SweetAlert2
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.cancel-booking-btn');
            if (!btn) return;

            e.preventDefault();
            const form = btn.closest('form.cancel-booking-form');
            const name = form?.dataset?.courseName || '';
            const id = form?.dataset?.bookingId || '';

            Swal.fire({
                icon: 'warning',
                title: @json(__('messages.cancel_booking_confirm_title')),
                html: (name ? '<b>' + name + '</b><br>' : '') + @json(__('messages.cancel_booking_confirm')) + (id ?
                    '<br><small>#' + id + '</small>' : ''),
                showCancelButton: true,
                confirmButtonText: @json(__('messages.cancel_booking')),
                cancelButtonText: @json(__('messages.back')),
                confirmButtonColor: '#d33',
                reverseButtons: true
            }).then(res => {
                if (res.isConfirmed) form.submit();
            });
        });
    </script>
@endsection
