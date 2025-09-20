{{-- resources/views/admin/ordershow.blade.php --}}
@extends('admin.layout')

@section('content')
    @php
        $badgeClass = match ($order->status) {
            'รออนุมัติ' => 'st-wait',
            'อนุมัติแล้ว' => 'st-OK',
            'กำลังจัดส่ง' => 'st-ship',
            'จัดส่งแล้ว' => 'st-done',
            default => 'st-wait',
        };

        // แผนที่ประเทศจากโค้ดที่ลูกค้ากรอกหน้า checkout
        $countryLabel =
            [
                'TH' => 'Thailand (TH)',
                'MY' => 'Malaysia (MY)',
            ][$order->country ?? 'TH'] ??
            ($order->country ?? '—');

        // ตัวเลขเสริม (ถ้ามีเก็บในออเดอร์)
        $shippingFee = (float) ($order->shipping_fee ?? 0);
        $boxFee = (float) ($order->box_fee ?? 0);
        $handlingFee = (float) ($order->handling_fee ?? 0);
        $totalMYR = $order->total_myr ?? null;
        $rateMYR = $order->rate_myr ?? null;
    @endphp

    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --paper: #faf7f2;
            --brand: #6b4e2e;
            --danger: #e03131;
        }

        .show-wrap {
            border: 1px solid #eee;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .show-head {
            background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
            border-bottom: 1px solid #f0f0f0;
            padding: 16px 18px;
        }

        .title {
            font-weight: 800;
            letter-spacing: .2px;
            color: var(--ink);
            margin: 0;
        }

        .meta dt {
            color: var(--muted);
        }

        .status-badge {
            font-weight: 700;
            border-radius: 999px;
            padding: 6px 12px;
        }

        .st-wait {
            background: #fff7e6;
            color: #b35c00;
        }

        .st-OK {
            background: #e7f5ff;
            color: #0b7285;
        }

        .st-ship {
            background: #f3f0ff;
            color: #5f3dc4;
        }

        .st-done {
            background: #e6fcf5;
            color: #0c6b58;
        }

        .thumb {
            width: 100%;
            max-width: 320px;
            aspect-ratio: 4/3;
            object-fit: cover;
            border: 1px solid #eee;
            border-radius: 12px;
            background: #fafafa;
        }

        .sticky-actions {
            position: sticky;
            bottom: 0;
            z-index: 5;
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(6px);
            border-top: 1px solid #eee;
            padding: 12px 0;
            margin-top: 16px;
        }

        .badge-country {
            background: #f3f4f6;
            color: #374151;
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 700
        }

        .addr-box {
            white-space: pre-wrap;
        }
    </style>

    <div class="container mt-3">
        <div class="show-wrap">
            <div class="show-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="title">รายละเอียดคำสั่งซื้อ #{{ $order->id }}</h2>
                    <small class="text-muted">สร้างเมื่อ {{ $order->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <div class="row g-0">
                {{-- LEFT: ผู้สั่งซื้อ + การชำระเงิน + จัดส่ง --}}
                <div class="col-12 col-lg-5 p-3 p-lg-4 border-end">
                    <dl class="row meta mb-3">
                        <dt class="col-sm-4">ผู้สั่งซื้อ</dt>
                        <dd class="col-sm-8">{{ $order->name ?? '—' }}</dd>

                        <dt class="col-sm-4">ยอดรวม</dt>
                        <dd class="col-sm-8">฿ {{ number_format($order->total_price, 2) }}</dd>

                        <dt class="col-sm-4">สถานะ</dt>
                        <dd class="col-sm-8"><span class="status-badge {{ $badgeClass }}">{{ $order->status }}</span></dd>
                    </dl>

                    {{-- ✅ ข้อมูลการจัดส่ง --}}
                    <div class="border rounded-3 p-3 mb-3">
                        <h6 class="mb-3" style="font-weight:800;color:var(--ink)">ข้อมูลการจัดส่ง</h6>

                        <dl class="row meta mb-0">
                            <dt class="col-sm-4">ผู้รับ</dt>
                            <dd class="col-sm-8">{{ $order->recipient_name ?? ($order->name ?? '—') }}</dd>

                            <dt class="col-sm-4">โทร</dt>
                            <dd class="col-sm-8">{{ $order->phone ?? '—' }}</dd>

                            <dt class="col-sm-4">ประเทศ</dt>
                            <dd class="col-sm-8"><span class="badge-country">{{ $countryLabel }}</span></dd>

                            <dt class="col-sm-4">ที่อยู่</dt>
                            <dd class="col-sm-8 addr-box">{!! nl2br(e($order->address ?? '—')) !!}</dd>
                        </dl>
                    </div>

                    {{-- สลิปการชำระเงิน --}}
                    <div class="mt-3">
                        <label class="form-label d-block"><strong>สลิปการชำระเงิน</strong></label>
                        @if (!empty($order->payment_slip_path))
                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                <a href="{{ asset('storage/' . $order->payment_slip_path) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> เปิดไฟล์
                                </a>
                                @php $ext = strtolower(pathinfo($order->payment_slip_path, PATHINFO_EXTENSION)); @endphp
                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#slipModal">
                                        <i class="bi bi-image"></i> ดูตัวอย่าง
                                    </button>
                                @endif
                            </div>

                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $order->payment_slip_path) }}" alt="Payment slip"
                                        class="thumb">
                                </div>
                                <div class="modal fade" id="slipModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">สลิปการชำระเงิน</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $order->payment_slip_path) }}"
                                                    alt="Payment slip" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">ยังไม่มีสลิปแนบมาพร้อมคำสั่งซื้อนี้</div>
                        @endif
                    </div>
                </div>

                {{-- RIGHT: อัปเดตสถานะ + รายการสินค้า + สรุปค่าใช้จ่าย --}}
                <div class="col-12 col-lg-7 p-3 p-lg-4">
                    {{-- อัปเดตสถานะ --}}
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="mb-3">
                        @csrf
                        <label for="status" class="form-label">อัปเดตสถานะ</label>
                        <div class="input-group" style="max-width:420px;">
                            <select name="status" id="status" class="form-select">
                                <option value="รออนุมัติ" @selected($order->status == 'รออนุมัติ')>รออนุมัติ</option>
                                <option value="อนุมัติแล้ว" @selected($order->status == 'อนุมัติแล้ว')>อนุมัติแล้ว</option>
                                <option value="กำลังจัดส่ง" @selected($order->status == 'กำลังจัดส่ง')>กำลังจัดส่ง</option>
                                <option value="จัดส่งแล้ว" @selected($order->status == 'จัดส่งแล้ว')>จัดส่งแล้ว</option>
                            </select>
                            <button type="submit" class="btn btn-success"><i class="bi bi-save2"></i> บันทึก</button>
                        </div>
                    </form>

                    {{-- รายการสินค้า --}}
                    <h5 class="mb-2">สินค้าในคำสั่งซื้อ</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>สินค้า</th>
                                    <th class="text-center" style="width:110px;">จำนวน</th>
                                    <th class="text-end" style="width:150px;">ราคา/หน่วย</th>
                                    <th class="text-end" style="width:150px;">รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sum = 0; @endphp
                                @foreach ($order->items as $item)
                                    @php
                                        $line = (float) $item->price * (int) $item->quantity;
                                        $sum += $line;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">฿ {{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">฿ {{ number_format($line, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="3" class="text-end">รวม (คำนวณจากรายการ)</th>
                                    <th class="text-end">฿ {{ number_format($sum, 2) }}</th>
                                </tr>

                                {{-- ✅ แสดงค่าใช้จ่ายการจัดส่ง/กล่อง/จัดการ ถ้ามีในออเดอร์ --}}
                                @if ($shippingFee > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">ค่าส่ง</th>
                                        <th class="text-end">฿ {{ number_format($shippingFee, 2) }}</th>
                                    </tr>
                                @endif
                                @if ($boxFee > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">ค่ากล่อง</th>
                                        <th class="text-end">฿ {{ number_format($boxFee, 2) }}</th>
                                    </tr>
                                @endif
                                @if ($handlingFee > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">ค่าดำเนินการ</th>
                                        <th class="text-end">฿ {{ number_format($handlingFee, 2) }}</th>
                                    </tr>
                                @endif

                                <tr>
                                    <th colspan="3" class="text-end">ยอดรวมตามออเดอร์</th>
                                    <th class="text-end">฿ {{ number_format($order->total_price, 2) }}</th>
                                </tr>

                                {{-- ✅ ถ้าส่งมาเลย์และมีรวมเป็น MYR --}}
                                @if (($order->country ?? '') === 'MY' && $totalMYR)
                                    <tr>
                                        <th colspan="3" class="text-end">≈ รวมเป็นเงินมาเลย์</th>
                                        <th class="text-end">{{ number_format((float) $totalMYR, 2) }} RM</th>
                                    </tr>
                                    @if ($rateMYR)
                                        <tr>
                                            <th colspan="4" class="text-end text-muted fw-normal">อัตราแปลงที่ใช้:
                                                {{ number_format((float) $rateMYR, 4) }} MYR</th>
                                        </tr>
                                    @endif
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    {{-- bottom actions on mobile --}}
                    <div class="d-lg-none sticky-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i
                                    class="bi bi-arrow-left"></i> กลับ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- หมายเหตุ: ถ้าใช้ SweetAlert2 ไว้ใน layout แล้ว จะโชว์กลางจอจาก session("success") อัตโนมัติ --}}
@endsection
