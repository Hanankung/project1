@extends('admin.layout')

@section('content')
    @php
        $total = $orders->count();
        $sumTHB = number_format($orders->sum('total_price'), 2);
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

        .status-badge {
            font-weight: 700;
            border-radius: 999px;
            padding: 4px 10px;
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

        .table thead th {
            background: #fafafa;
        }

        .table-hover tbody tr:hover {
            background: #fffdf5;
        }
    </style>

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="container mt-3">
        {{-- HERO / SUMMARY --}}
        <div class="admin-hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">รายการคำสั่งซื้อทั้งหมด
                    </h1>
                    <span class="stat-pill"><i class="bi bi-receipt"></i> ทั้งหมด <span
                            class="num">{{ $total }}</span></span>
                    <span class="stat-pill"><i class="bi bi-currency-exchange"></i> ยอดรวม <span class="num">฿
                            {{ $sumTHB }}</span></span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">รีเฟรช</a>
            </div>

            {{-- Filters --}}
            <div class="filter-bar mt-3">
                <input type="search" id="q" class="form-control search-input"
                    placeholder="ค้นหาชื่อผู้สั่งซื้อ / ไอดีออเดอร์ / สถานะ... (กรองในหน้า)">
                <select id="statusFilter" class="form-select" style="max-width:220px;">
                    <option value="">ทุกสถานะ</option>
                    <option value="รออนุมัติ">รออนุมัติ</option>
                    <option value="อนุมัติแล้ว">อนุมัติแล้ว</option>
                    <option value="กำลังจัดส่ง">กำลังจัดส่ง</option>
                    <option value="จัดส่งแล้ว">จัดส่งแล้ว</option>
                </select>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" id="ordersTable">
                <thead>
                    <tr>
                        <th style="width:70px;">#</th>
                        <th>ผู้สั่งซื้อ</th>
                        <th style="width:150px;">ยอดรวม</th>
                        <th style="width:140px;">สถานะ</th>
                        <th style="width:170px;">วันที่สั่งซื้อ</th>
                        <th style="width:130px;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $badgeClass = match ($order->status) {
                                'รออนุมัติ' => 'st-wait',
                                'อนุมัติแล้ว' => 'st-OK',
                                'กำลังจัดส่ง' => 'st-ship',
                                'จัดส่งแล้ว' => 'st-done',
                                default => 'st-wait',
                            };
                        @endphp
                        <tr data-keywords="{{ \Illuminate\Support\Str::lower($order->id . ' ' . $order->name . ' ' . $order->status) }}"
                            data-status="{{ $order->status }}">
                            <td class="text-muted">#{{ $order->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->name }}</div>
                                <small class="text-muted">ID: {{ $order->id }}</small>
                            </td>
                            <td>฿ {{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="status-badge {{ $badgeClass }}">{{ $order->status }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Filter script --}}
    <script>
        (function() {
            const q = document.getElementById('q');
            const sf = document.getElementById('statusFilter');
            const rows = Array.from(document.querySelectorAll('#ordersTable tbody tr'));

            function apply() {
                const needle = (q?.value || '').trim().toLowerCase();
                const st = sf?.value || '';
                rows.forEach(tr => {
                    const kw = tr.getAttribute('data-keywords') || '';
                    const okSearch = kw.includes(needle);
                    const okStatus = !st || (tr.getAttribute('data-status') === st);
                    tr.style.display = (okSearch && okStatus) ? '' : 'none';
                });
            }
            q?.addEventListener('input', apply);
            sf?.addEventListener('change', apply);
        })();
    </script>
@endsection
