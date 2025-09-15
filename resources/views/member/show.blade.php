@extends('member.layout')

@section('content')

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    {{-- ฟอนต์อ่านสวยสำหรับ TH/EN/MS (ไม่กระทบฟังก์ชัน) --}}
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
          --brand:#6b4e2e;   /* โทนหลักน้ำตาลพรีเมี่ยม */
          --gold-1:#d4af37;  /* ทอง */
          --gold-2:#f6e27a;  /* ทองอ่อน */
          --ink:#1f2937;
          --muted:#6b7280;
        }
        *{font-family:"Prompt",system-ui,-apple-system,"Segoe UI",Roboto,"Noto Sans Thai",Arial,sans-serif}
        body{background:linear-gradient(180deg,#faf7f2 0%, #fff 60%)}

        /* หัวข้อใหญ่ */
        .page-title{
          font-weight:800; letter-spacing:.2px; margin: .25rem 0 1rem;
          background:linear-gradient(120deg,var(--gold-1),var(--gold-2),var(--gold-1));
          -webkit-background-clip:text; background-clip:text; color:transparent;
        }

        /* การ์ด/กล่อง */
        .card{
          border-radius:18px !important;
          border:1px solid rgba(139,106,70,.12);
          box-shadow:0 18px 44px rgba(0,0,0,.08);
          overflow:hidden; background:#fff;
        }
        .table{margin-bottom:0}
        .table thead th{
          background:linear-gradient(180deg,#fff,#fff8e6);
          border-bottom:1px solid rgba(212,175,55,.25) !important;
          color:#475467 !important;
        }
        .table tbody tr:hover{background:#fffdf5}
        .table tfoot th{font-size:1.02rem}
        .table-dark{ --bs-table-bg:#1f2937; } /* แถว "ราคารวมทั้งหมด" */

        .alert{border-radius:14px}
        .alert-warning{border-color:rgba(212,175,55,.35)}

        /* ปุ่ม */
        .btn{border-radius:12px}
        .btn-danger{box-shadow:0 10px 24px rgba(220,53,69,.15)}
        .btn-info{box-shadow:0 10px 24px rgba(13,202,240,.15)}
        .btn-secondary{box-shadow:0 10px 24px rgba(108,117,125,.12)}

        /* ป้ายสถานะ (badge) */
        .status-chip{
          display:inline-block; padding:.35rem .7rem; border-radius:999px;
          font-weight:700; font-size:.875rem; border:1px solid transparent;
        }
        .status-pending{ background:#fff3cd; color:#664d03; border-color:#ffe69c; }
        .status-approved{ background:#d1e7dd; color:#0f5132; border-color:#a3cfbb; }
        .status-rejected{ background:#f8d7da; color:#842029; border-color:#f5c2c7; }
        .status-status_shipped{ background:#cfe2ff; color:#084298; border-color:#9ec5fe; }
        .status-status_delivered{ background:#d1e7dd; color:#0f5132; border-color:#a3cfbb; }
        .status-status_cancelled{ background:#e2e3e5; color:#41464b; border-color:#d3d6d8; }
    </style>
</head>

<div class="container mt-5">

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- สร้างแผนที่สถานะ (ไทย/คีย์เดิม -> คีย์กลาง) ใช้ทั้งหน้า --}}
    @php
        $statusMap = [
            'รอดำเนินการ' => 'pending',
            'อนุมัติแล้ว' => 'approved',
            'ไม่อนุมัติ' => 'rejected',
            'กำลังจัดส่งแล้ว' => 'status_shipped',
            'จัดส่งสำเร็จ' => 'status_delivered',
            'ยกเลิก' => 'status_cancelled',
            'pending' => 'pending',
            'approved' => 'approved',
            'rejected' => 'rejected',
            'status_shipped' => 'status_shipped',
            'status_delivered' => 'status_delivered',
            'status_cancelled' => 'status_cancelled',
        ];
        // helper แบบเบา ๆ เพื่อเลือกคลาสสีของ badge
        $statusClass = function($key){
            return match($key){
                'pending' => 'status-pending',
                'approved' => 'status-approved',
                'rejected' => 'status-rejected',
                'status_shipped' => 'status-status_shipped',
                'status_delivered' => 'status-status_delivered',
                'status_cancelled' => 'status-status_cancelled',
                default => 'status-pending'
            };
        };
    @endphp

    {{-- รายการออเดอร์ทั้งหมด --}}
    @if (isset($orders))
        <h2 class="page-title">{{ __('messages.Order History') }}</h2>

        @if ($orders->isEmpty())
            <div class="alert alert-info mt-3">{{ __('messages.warn1') }}</div>
        @else
            <div class="card mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.Order date') }}</th>
                                <th>{{ __('messages.Total price') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.detail') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $rawStatus = $order->status ?? $order->status_i18n_key;
                                    $statusKey = $statusMap[$rawStatus] ?? $rawStatus;
                                @endphp
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}</td>

                                    {{-- สถานะแบบ badge แปลภาษา --}}
                                    <td>
                                        <span class="status-chip {{ $statusClass($statusKey) }}">
                                            {{ __('messages.status_order.' . $statusKey) }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('member.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                            {{ __('messages.description') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($statusKey === 'pending')
                                            <form action="{{ route('member.orders.cancel', $order->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('{{ __('messages.cancel_confirm') }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    {{ __('messages.cancel_order') }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                {{ __('messages.cancel_unavailable') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    {{-- รายละเอียดออเดอร์ตัวเดียว --}}
    @elseif (isset($order))
        @php
            $rawStatus = $order->status ?? $order->status_i18n_key;
            $statusKey = $statusMap[$rawStatus] ?? $rawStatus;
            $rateMyr = (float) config('currency.rates.THB_MYR', 0.13);
            $isMY = ($order->country ?? 'TH') === 'MY';
        @endphp

        <h2 class="page-title">{{ __('messages.Order details') }} #{{ $order->id }}</h2>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>{{ __('messages.Buyer') }}:</strong></p>
                        <div>{{ $order->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>{{ __('messages.Phone') }}:</strong></p>
                        <div>{{ $order->phone }}</div>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>{{ __('messages.status') }}:</strong></p>
                        <div>
                            <span class="status-chip {{ $statusClass($statusKey) }}">
                                {{ __('messages.status_order.' . $statusKey) }}
                            </span>
                        </div>
                    </div>

                    {{-- ลิงก์สลิปถ้ามี --}}
                    @if (!empty($order->payment_slip_path))
                        <div class="col-md-3">
                            <p class="mb-1"><strong>{{ __('messages.Payment slip') }}:</strong></p>
                            <div>
                                <a href="{{ asset('storage/' . $order->payment_slip_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                  <i class="bi bi-receipt"></i> {{ __('messages.Open Slip View') }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <p class="mb-1"><strong>{{ __('messages.Address') }}:</strong></p>
                        <div>{{ $order->address }}</div>
                    </div>
                    <div class="col-12">
                        <p class="mb-1"><strong>{{ __('messages.Total price') }}:</strong></p>
                        <div>{{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-2" style="color:var(--brand); font-weight:700">{{ __('messages.Product List') }}</h4>
        <div class="card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th class="text-center">{{ __('messages.quantity') }}</th>
                            <th class="text-end">{{ __('messages.price') }}/{{ __('messages.Piece') }}</th>
                            <th class="text-end">{{ __('messages.line_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    @php
                                        $locale = app()->getLocale();
                                        if ($locale === 'th')      echo $item->product->product_name;
                                        elseif ($locale === 'en')  echo $item->product->product_name_ENG;
                                        elseif ($locale === 'ms')  echo $item->product->product_name_MS;
                                    @endphp
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">{{ __('messages.subtotal') }}</th>
                            <th class="text-end">
                                {{ number_format($order->subtotal ?? 0, 2) }} {{ __('messages.baht') }}
                            </th>
                        </tr>
                        @if (($order->shipping_fee ?? 0) > 0)
                            <tr>
                                <th colspan="3" class="text-end">{{ __('messages.shipping') }}</th>
                                <th class="text-end">
                                    {{ number_format($order->shipping_fee, 2) }} {{ __('messages.baht') }}
                                </th>
                            </tr>
                        @endif
                        @if (($order->box_fee ?? 0) > 0)
                            <tr>
                                <th colspan="3" class="text-end">{{ __('messages.box') }}</th>
                                <th class="text-end">
                                    {{ number_format($order->box_fee, 2) }} {{ __('messages.baht') }}
                                </th>
                            </tr>
                        @endif
                        @if (($order->handling_fee ?? 0) > 0)
                            <tr>
                                <th colspan="3" class="text-end">{{ __('messages.handling') }}</th>
                                <th class="text-end">
                                    {{ number_format($order->handling_fee, 2) }} {{ __('messages.baht') }}
                                </th>
                            </tr>
                        @endif

                        <tr class="table-dark">
                            <th colspan="3" class="text-end">{{ __('messages.grand_total') }}</th>
                            <th class="text-end">
                                {{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}
                                @if (($order->country ?? 'TH') === 'MY')
                                    <br><small class="text-muted">≈ {{ number_format($order->total_price * $rateMyr, 2) }} RM</small>
                                @endif
                            </th>
                        </tr>

                        @if ($isMY)
                            <tr>
                                <th colspan="3" class="text-end">
                                    ≈ {{ __('messages.Total_MY') }}
                                    <small class="text-muted d-block">
                                        {{ __('messages.exchange rate') }} = {{ number_format($rateMyr, 4) }} MYR
                                    </small>
                                </th>
                                <th class="text-end">
                                    {{ number_format($order->total_price * $rateMyr, 2) }} RM
                                </th>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('member.orders') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
            </a>

            @if ($statusKey === 'pending')
                <form action="{{ route('member.orders.cancel', $order->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('{{ __('messages.cancel_confirm') }}');">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> {{ __('messages.cancel_order') }}
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection
