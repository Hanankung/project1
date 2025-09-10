@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
                // เก็บไทยใน DB
                'รอดำเนินการ' => 'pending',
                'อนุมัติแล้ว' => 'approved',
                'ไม่อนุมัติ' => 'rejected',
                'กำลังจัดส่งแล้ว' => 'status_shipped',
                'จัดส่งสำเร็จ' => 'status_delivered',
                'ยกเลิก' => 'status_cancelled',
                // กรณี DB เก็บคีย์กลางอยู่แล้ว
                'pending' => 'pending',
                'approved' => 'approved',
                'rejected' => 'rejected',
                'status_shipped' => 'status_shipped',
                'status_delivered' => 'status_delivered',
                'status_cancelled' => 'status_cancelled',
            ];
        @endphp

        {{-- รายการออเดอร์ทั้งหมด --}}
        @if (isset($orders))
            <h2>{{ __('messages.Order History') }}</h2>

            @if ($orders->isEmpty())
                <div class="alert alert-info mt-3">{{ __('messages.warn1') }}</div>
            @else
                <table class="table table-bordered mt-3">
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

                                {{-- แสดงสถานะแบบแปลภาษา --}}
                                <td>{{ __('messages.status_order.' . $statusKey) }}</td>

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
            @endif

            {{-- รายละเอียดออเดอร์ตัวเดียว --}}
        @elseif (isset($order))
            @php
                $rawStatus = $order->status ?? $order->status_i18n_key;
                $statusKey = $statusMap[$rawStatus] ?? $rawStatus;
            @endphp

            <h2>{{ __('messages.Order details') }} #{{ $order->id }}</h2>

            <div class="row g-3 mb-3">
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
                    <div>{{ __('messages.status_order.' . $statusKey) }}</div>
                </div>
                {{-- ✅ คอลัมน์ลิงก์สลิป --}}
                @if (!empty($order->payment_slip_path))
                    <div class="col-md-3">
                        <p class="mb-1"><strong>สลิปการชำระเงิน:</strong></p>
                        <div>
                            <a href="{{ asset('storage/' . $order->payment_slip_path) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                เปิดดูสลิป
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

            <h4 class="mt-4">{{ __('messages.Product List') }}</h4>
            @php
                $rateMyr = (float) config('currency.rates.THB_MYR', 0.13);
                $isMY = ($order->country ?? 'TH') === 'MY';
            @endphp
            <div class="table-responsive">
                <table class="table align-middle">
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
                                        if ($locale === 'th') {
                                            echo $item->product->product_name;
                                        } elseif ($locale === 'en') {
                                            echo $item->product->product_name_ENG;
                                        } elseif ($locale === 'ms') {
                                            echo $item->product->product_name_MS;
                                        }
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

                        {{-- <tr class="table-dark">
                            <th colspan="3" class="text-end">{{ __('messages.grand_total') }}</th>
                            <th class="text-end">
                                {{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}
                            </th>
                        </tr> --}}

                        <tr class="table-dark">
                            <th colspan="3" class="text-end">{{ __('messages.grand_total') }}</th>
                            <th class="text-end">
                                {{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}
                                @php
                                    $rateMyr = (float) config('currency.rates.THB_MYR', 0.13);
                                @endphp
                                @if (($order->country ?? 'TH') === 'MY')
                                    <br>
                                    <small class="text-muted">
                                        ≈ {{ number_format($order->total_price * $rateMyr, 2) }} RM
                                    </small>
                                @endif
                            </th>
                        </tr>

                        @if ($isMY)
                            <tr>
                                <th colspan="3" class="text-end">
                                    ≈ รวมเป็นเงินมาเลเซีย (MYR)
                                    <small class="text-muted d-block">
                                        ใช้อัตราแลกเปลี่ยน 1 THB = {{ number_format($rateMyr, 4) }} MYR
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
        @endif
    </div>
@endsection
