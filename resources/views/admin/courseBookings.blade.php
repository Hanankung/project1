{{-- resources/views/admin/courseBookings.blade.php --}}
@extends('admin.layout')

@section('content')
    @php
        // รวมยอดโดยคำนวณเผื่อบางรายการไม่มี total_price
        $sumTHB = $bookings->sum(function ($b) {
            $u = (float) ($b->price ?? 0);
            $q = (int) ($b->quantity ?? 0);
            return isset($b->total_price) && $b->total_price !== null ? (float) $b->total_price : $u * $q;
        });
    @endphp

    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --paper: #faf7f2;
            --brand: #6b4e2e;
            --brand2: #8a6b47;
        }

        .admin-hero {
            background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
        }

        .stat-pill {
            border: 1px solid #eee;
            background: #fff;
            border-radius: 14px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .03);
        }

        .stat-pill .num {
            font-weight: 800;
            color: var(--ink)
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            border-radius: 12px !important;
            padding: 10px 14px !important;
            border: 1px solid #e5e7eb !important;
        }

        .table thead th {
            background: #fafafa;
        }

        .table-hover tbody tr:hover {
            background: #fffdf5;
        }

        .status-badge {
            font-weight: 700;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .st-pending {
            background: #fff7e6;
            color: #b35c00;
        }

        .st-approve {
            background: #e6fcf5;
            color: #0c6b58;
        }

        .st-reject {
            background: #ffe3e3;
            color: #c92a2a;
        }

        .st-other {
            background: #f3f4f6;
            color: #374151;
        }
    </style>

    <div class="container mt-4">
        {{-- Flash success --}}
        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        {{-- HERO / SUMMARY --}}
        <div class="admin-hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">
                        รายการจองคอร์สเรียนทั้งหมด</h1>
                    <span class="stat-pill"><i class="bi bi-people"></i> ทั้งหมด <span
                            class="num">{{ $bookings->count() }}</span></span>
                    <span class="stat-pill"><i class="bi bi-currency-exchange"></i> ยอดรวม <span class="num">฿
                            {{ number_format($sumTHB, 2) }}</span></span>
                </div>
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">รีเฟรช</a>
            </div>

            {{-- Filters --}}
            <div class="filter-bar mt-3">
                <input type="search" id="q" class="form-control search-input"
                    placeholder="ค้นหาชื่อ/คอร์ส/วันที่/สถานะ... (กรองในหน้า)">
                <select id="statusFilter" class="form-select" style="max-width:220px;">
                    <option value="">ทุกสถานะ</option>
                    <option value="รอดำเนินการ">รอดำเนินการ</option>
                    <option value="อนุมัติ">อนุมัติ</option>
                    <option value="ไม่อนุมัติ">ไม่อนุมัติ</option>
                </select>
                <select id="slipFilter" class="form-select" style="max-width:180px;">
                    <option value="">สลิปทั้งหมด</option>
                    <option value="has">มีสลิป</option>
                    <option value="none">ไม่มีสลิป</option>
                </select>
            </div>
        </div>

        @if ($bookings->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="bookingsTable">
                    <thead>
                        <tr>
                            <th style="min-width:180px;">ชื่อผู้จอง</th>
                            <th>คอร์ส</th>
                            <th style="min-width:130px;">วันที่จอง</th>
                            <th class="text-end" style="width:90px;">จำนวน</th>
                            <th class="text-end" style="width:130px;">ราคา/คน</th>
                            <th class="text-end" style="width:140px;">ราคารวม</th>
                            <th class="text-center" style="width:110px;">สลิป</th>
                            <th class="text-center" style="width:130px;">สถานะ</th>
                            <th class="text-center" style="min-width:200px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            @php
                                $unit = (float) ($booking->price ?? 0);
                                $qty = (int) ($booking->quantity ?? 0);
                                $total =
                                    isset($booking->total_price) && $booking->total_price !== null
                                        ? (float) $booking->total_price
                                        : $unit * $qty;
                                $hasSlip = !empty($booking->payment_slip);
                                // $url = $hasSlip
                                //     ? \Illuminate\Support\Facades\Storage::url($booking->payment_slip)
                                //     : null;
                                $url = $hasSlip
                                    ? asset('storage/' . $booking->payment_slip)
                                    : null;
                                $ext = $hasSlip
                                    ? strtolower(pathinfo(parse_url($url ?? '', PHP_URL_PATH), PATHINFO_EXTENSION))
                                    : null;
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $badge =
                                    $booking->status === 'รอดำเนินการ'
                                        ? 'st-pending'
                                        : ($booking->status === 'อนุมัติ'
                                            ? 'st-approve'
                                            : ($booking->status === 'ไม่อนุมัติ'
                                                ? 'st-reject'
                                                : 'st-other'));
                            @endphp
                            <tr data-keywords="{{ \Illuminate\Support\Str::lower($booking->name . ' ' . $booking->lastname . ' ' . $booking->course_name . ' ' . $booking->booking_date . ' ' . $booking->status ?? '') }}"
                                data-status="{{ $booking->status }}" data-slip="{{ $hasSlip ? 'has' : 'none' }}">
                                <td>
                                    <div class="fw-semibold">{{ $booking->name }} {{ $booking->lastname }}</div>
                                    @if (!empty($booking->email))
                                        <small><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></small>
                                    @endif
                                </td>
                                <td>{{ $booking->course_name }}</td>
                                <td>{{ $booking->booking_date }}</td>
                                <td class="text-end">{{ $qty }}</td>
                                <td class="text-end">฿ {{ number_format($unit, 2) }}</td>
                                <td class="text-end fw-semibold">฿ {{ number_format($total, 2) }}</td>

                                {{-- สลิป --}}
                                <td class="text-center">
                                    @if ($hasSlip)
                                        @if ($isImage)
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#slip-{{ $booking->id }}">ดูสลิป</button>
                                            {{-- Modal รูป --}}
                                            <div class="modal fade" id="slip-{{ $booking->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">สลิปการชำระเงิน #{{ $booking->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ $url }}" class="img-fluid rounded w-100"
                                                                alt="payment slip">
                                                            <div class="mt-2">
                                                                <a href="{{ $url }}" target="_blank"
                                                                    rel="noopener"
                                                                    class="btn btn-outline-secondary btn-sm">เปิดไฟล์ต้นฉบับ</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a class="btn btn-outline-primary btn-sm" href="{{ $url }}"
                                                target="_blank" rel="noopener">เปิดสลิป (PDF)</a>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">ไม่มีสลิป</span>
                                    @endif
                                </td>

                                {{-- สถานะ --}}
                                <td class="text-center">
                                    <span class="status-badge {{ $badge }}">{{ $booking->status }}</span>
                                </td>

                                {{-- จัดการ --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#booking-{{ $booking->id }}">รายละเอียด</button>

                                    @if ($booking->status === 'รอดำเนินการ' && $hasSlip)
                                        <form action="{{ route('admin.course.booking.approve', $booking->id) }}"
                                            method="POST" class="d-inline" data-swal-submit="approve">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success btn-sm">อนุมัติ</button>
                                        </form>
                                        <form action="{{ route('admin.course.booking.reject', $booking->id) }}"
                                            method="POST" class="d-inline" data-swal-submit="reject">
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
                                                รายละเอียดการจอง #{{ $booking->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">ผู้จอง</dt>
                                                <dd class="col-sm-8">{{ $booking->name }} {{ $booking->lastname }}</dd>

                                                <dt class="col-sm-4">อีเมล</dt>
                                                <dd class="col-sm-8">
                                                    @if ($booking->email)
                                                        <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </dd>

                                                <dt class="col-sm-4">โทร</dt>
                                                <dd class="col-sm-8">
                                                    @if ($booking->phone)
                                                        <a href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </dd>

                                                <dt class="col-sm-4">คอร์ส</dt>
                                                <dd class="col-sm-8">{{ $booking->course_name }}</dd>

                                                <dt class="col-sm-4">วันที่จอง</dt>
                                                <dd class="col-sm-8">{{ $booking->booking_date }}</dd>

                                                <dt class="col-sm-4">จำนวน</dt>
                                                <dd class="col-sm-8">{{ $qty }} คน</dd>

                                                <dt class="col-sm-4">ราคา/คน</dt>
                                                <dd class="col-sm-8">฿ {{ number_format($unit, 2) }}</dd>

                                                <dt class="col-sm-4">ราคารวม</dt>
                                                <dd class="col-sm-8 fw-semibold">฿ {{ number_format($total, 2) }}</dd>

                                                <dt class="col-sm-4">สถานะ</dt>
                                                <dd class="col-sm-8">{{ $booking->status }}</dd>

                                                @if (!empty($booking->payment_slip))
                                                    <dt class="col-sm-4">สลิป</dt>
                                                    <dd class="col-sm-8">
                                                        @if ($isImage)
                                                            <img src="{{ $url }}"
                                                                class="img-fluid rounded border" alt="payment slip">
                                                        @else
                                                            <a href="{{ $url }}" target="_blank" rel="noopener"
                                                                class="btn btn-outline-primary btn-sm">เปิดสลิป (PDF)</a>
                                                        @endif
                                                    </dd>
                                                @endif

                                                @if (isset($booking->created_at))
                                                    <dt class="col-sm-4">สร้างเมื่อ</dt>
                                                    <dd class="col-sm-8">{{ $booking->created_at }}</dd>
                                                @endif
                                            </dl>
                                        </div>
                                        <div class="modal-footer">
                                            @if ($booking->status === 'รอดำเนินการ' && $hasSlip)
                                                <form action="{{ route('admin.course.booking.approve', $booking->id) }}"
                                                    method="POST" class="me-auto" data-swal-submit="approve">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-success">อนุมัติ</button>
                                                </form>
                                                <form action="{{ route('admin.course.booking.reject', $booking->id) }}"
                                                    method="POST" data-swal-submit="reject">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-danger">ไม่อนุมัติ</button>
                                                </form>
                                            @elseif($booking->status === 'รอดำเนินการ' && !$hasSlip)
                                                <span class="text-muted small me-auto">รอสลิป</span>
                                            @endif
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">ยังไม่มีการจองคอร์ส</div>
        @endif
    </div>

    {{-- Filter + SweetAlert2 confirm --}}
    <script>
        (function() {
            // ค้นหา/กรอง
            const q = document.getElementById('q');
            const sf = document.getElementById('statusFilter');
            const sl = document.getElementById('slipFilter');
            const rows = Array.from(document.querySelectorAll('#bookingsTable tbody tr'));

            function apply() {
                const needle = (q?.value || '').trim().toLowerCase();
                const st = sf?.value || '';
                const slip = sl?.value || '';
                rows.forEach(tr => {
                    const kw = tr.getAttribute('data-keywords') || '';
                    const okKw = kw.includes(needle);
                    const okSt = !st || (tr.getAttribute('data-status') === st);
                    const okSl = !slip || (tr.getAttribute('data-slip') === slip);
                    tr.style.display = (okKw && okSt && okSl) ? '' : 'none';
                });
            }
            q?.addEventListener('input', apply);
            sf?.addEventListener('change', apply);
            sl?.addEventListener('change', apply);

            // ยืนยันอนุมัติ/ไม่อนุมัติด้วย SweetAlert2 (ถ้ามี)
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form[data-swal-submit]');
                if (!form) return;
                e.preventDefault();
                const type = form.getAttribute('data-swal-submit'); // approve | reject
                const isApprove = type === 'approve';
                const cfg = isApprove ?
                    {
                        icon: 'question',
                        title: 'ยืนยันอนุมัติ?',
                        text: 'ระบบจะบันทึกเป็น “อนุมัติ”',
                        confirmButtonText: 'อนุมัติ',
                        confirmButtonColor: '#16a34a'
                    } :
                    {
                        icon: 'warning',
                        title: 'ยืนยันไม่อนุมัติ?',
                        text: 'ระบบจะบันทึกเป็น “ไม่อนุมัติ”',
                        confirmButtonText: 'ไม่อนุมัติ',
                        confirmButtonColor: '#e03131'
                    };

                if (window.Swal) {
                    Swal.fire({
                        ...cfg,
                        showCancelButton: true,
                        cancelButtonText: 'ยกเลิก',
                        position: 'center'
                    }).then(res => {
                        if (res.isConfirmed) form.submit();
                    });
                } else {
                    if (confirm(cfg.title)) form.submit();
                }
            });
        })();
    </script>

    {{-- ถ้า layout ของคุณยังไม่ได้ใส่ Bootstrap JS ให้เปิดใช้งานบรรทัดนี้ด้านล่าง --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
@endsection
