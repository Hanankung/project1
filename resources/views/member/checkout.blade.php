@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        {{-- เพิ่มฟอนต์สวยอ่านง่าย (ไม่กระทบฟังก์ชัน) --}}
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            /* ===== THEME: พรีเมี่ยมโทนทอง–น้ำตาล (Styling only) ===== */
            :root {
                --brand: #6b4e2e;
                /* น้ำตาลพรีเมี่ยม */
                --gold-1: #d4af37;
                /* ทอง */
                --gold-2: #f6e27a;
                /* ทองอ่อน */
                --ink: #222;
                --muted: #6b7280;
                --ring: rgba(212, 175, 55, .35);
            }

            * {
                font-family: "Prompt", system-ui, -apple-system, "Segoe UI", Roboto, Arial, "Noto Sans Thai", sans-serif
            }

            /* พื้นหลังหน้าแบบนุ่ม ๆ */
            body {
                background: linear-gradient(180deg, #faf7f2 0%, #ffffff 60%);
            }

            /* ปุ่มย้อนกลับ (ไม่เปลี่ยน href เดิม) */
            .back-btn {
                position: sticky;
                top: 16px;
                display: inline-grid;
                place-items: center;
                width: 44px;
                height: 44px;
                border-radius: 999px;
                background: #fff;
                border: 1px solid #eee;
                color: #333;
                box-shadow: 0 12px 28px rgba(0, 0, 0, .08);
                text-decoration: none;
                margin-bottom: 8px;
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .back-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 16px 36px rgba(0, 0, 0, .10);
            }

            /* หัวข้อหลัก */
            .container.my-5>h2 {
                font-weight: 800;
                letter-spacing: .2px;
                color: var(--ink);
                background: linear-gradient(120deg, var(--gold-1), var(--gold-2), var(--gold-1));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-top: 8px;
            }

            /* Card look พรีเมี่ยม (ไม่แตะโครง) */
            .card {
                border-radius: 18px !important;
                border: 1px solid rgba(139, 106, 70, .12);
                box-shadow: 0 18px 44px rgba(0, 0, 0, .08);
                overflow: hidden;
                background: #fff;
            }

            .card-header {
                background: linear-gradient(180deg, #fff, #fff8e6);
                border-bottom: 1px solid rgba(212, 175, 55, .25) !important;
                color: #4a4a4a;
                font-weight: 700;
            }

            /* ฟอร์ม */
            .form-control,
            .form-select {
                border-radius: 12px;
                padding: 10px 12px;
                border-color: #e5e7eb;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--gold-1);
                box-shadow: 0 0 0 .2rem rgba(212, 175, 55, .15);
            }

            /* กล่องอัปสลิปเดิม: แต่งให้ดูเป็น dropzone (ยังใช้ input-group เดิม) */
            .input-group {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
            }

            #slip-filename {
                background: #fffdf5;
            }

            /* ตารางสรุป */
            .table thead.table-light th {
                color: #4a4a4a;
            }

            .table tbody tr td {
                vertical-align: middle;
            }

            .table tfoot th {
                font-size: 1.02rem;
            }

            .table-dark {
                --bs-table-bg: #1f2937;
            }

            /* ปุ่ม */
            .btn-success {
                border: none;
                border-radius: 12px;
                font-weight: 800;
                letter-spacing: .2px;
                background: linear-gradient(180deg, #34d399, #28a745) !important;
                color: #fff !important;
                box-shadow: 0 14px 28px rgba(40, 167, 69, .25);
                padding: 12px 16px;
            }

            .btn-outline-secondary {
                border-radius: 12px;
            }

            /* ทำให้การ์ดสรุปด้านขวา sticky โดยไม่แตะโครงสร้าง/ID */
            @media (min-width: 768px) {
                .row>.col-md-6:last-child>.card {
                    position: sticky;
                    top: 24px;
                }
            }

            /* รายละเอียดเล็ก ๆ */
            .alert-warning {
                border-radius: 14px;
                border-color: rgba(212, 175, 55, .35);
            }

            .text-muted {
                color: var(--muted) !important;
            }

            .card-header.text-white {
                color: #222 !important;
            }

            .card-header.bg-primary.text-white,
            .card-header.bg-secondary.text-white {
                color: #222 !important;
            }

            /* === Quantity stepper === */
            .qty-stepper {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 2px;
                background: #fff;
            }

            .qty-stepper .btn-qty {
                width: 32px;
                height: 32px;
                border: none;
                border-radius: 8px;
                background: #f3f4f6;
                color: #111827;
                display: grid;
                place-items: center;
                font-weight: 800;
                line-height: 1;
                cursor: pointer;
            }

            .qty-stepper .btn-qty:hover {
                background: #e5e7eb;
            }

            .qty-stepper input.qty-input {
                width: 48px;
                height: 32px;
                border: none;
                text-align: center;
                font-weight: 700;
                color: #111827;
                background: transparent;
            }

            .qty-stepper .btn-qty:disabled {
                opacity: .4;
                cursor: not-allowed;
            }

            /* ให้คอลัมน์จำนวนดูไม่อึดอัด */
            td.cell-qty {
                min-width: 130px
            }
        </style>
    </head>

    <div class="container my-5">
        <a href="{{ route('cart.store') }}" class="back-btn">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <h2 class="mb-4">{{ __('messages.Order Confirmation') }}</h2>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Flash alert --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            {{-- LEFT: Checkout form --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        {{ __('messages.Recipient information') }}
                    </div>
                    <div class="card-body">

                        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.Recipient name') }}</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', auth()->user()->name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('messages.Shipping address') }}</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('messages.Phone') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                    required>
                            </div>

                            {{-- Country --}}
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select name="country" id="country" class="form-select" required>
                                    @php
                                        $countries = [
                                            'TH' => 'Thailand',
                                            'MY' => 'Malaysia',
                                        ];
                                    @endphp
                                    @foreach ($countries as $code => $label)
                                        <option value="{{ $code }}"
                                            {{ old('country', $country ?? 'TH') === $code ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('messages.International') }}</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Summary + Submit button --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        {{ __('messages.Items in Cart') }}
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
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
                                        @php
                                            // เลือก id สำหรับ DOM/hidden
                                            $rowId = isset($item->id)
                                                ? $item->id
                                                : $item->product_id ?? optional($item->product)->id;
                                            $maxStock = (int) ($item->product->quantity ?? 1);
                                            $priceEach = (float) ($item->product->price ?? 0);

                                            // มีเฉพาะตอนมาจาก Cart model เท่านั้น
                                            $updateUrl =
                                                isset($item->id) &&
                                                \Illuminate\Support\Facades\Route::has('member.cart.update')
                                                    ? route('member.cart.update', $item->id)
                                                    : '';
                                        @endphp

                                        <td class="text-center cell-qty">
                                            <div class="qty-stepper" data-item-id="{{ $rowId }}"
                                                data-stock="{{ $maxStock }}" data-price="{{ $priceEach }}"
                                                data-update-url="{{ $updateUrl }}">
                                                <button type="button" class="btn-qty minus"
                                                    aria-label="decrease">−</button>
                                                <input type="number" class="qty-input" value="{{ (int) $item->quantity }}"
                                                    min="1" max="{{ $maxStock }}">
                                                <button type="button" class="btn-qty plus" aria-label="increase">+</button>
                                            </div>

                                            {{-- fallback เวลาส่งฟอร์ม --}}
                                            <input type="hidden" name="quantities[{{ $rowId }}]"
                                                id="qty-hidden-{{ $rowId }}" value="{{ (int) $item->quantity }}"
                                                form="checkout-form">
                                        </td>

                                        <td class="text-end line-total">
                                            {{ number_format($priceEach * $item->quantity, 2) }} ฿
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>

                                <tr>
                                    <th colspan="2" class="text-end">{{ __('messages.Total price') }}</th>
                                    <th id="subtotal" class="text-end">{{ number_format($subtotal, 2) }} ฿</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">{{ __('messages.shipping') }}</th>
                                    <th id="shipping" class="text-end">{{ number_format($quote['shipping'], 2) }} ฿</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">{{ __('messages.box') }}</th>
                                    <th id="box" class="text-end">{{ number_format($quote['box'], 2) }} ฿</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">{{ __('messages.handling') }}</th>
                                    <th id="handling" class="text-end">{{ number_format($quote['handling'], 2) }} ฿</th>
                                </tr>
                                <tr class="table-dark">
                                    <th colspan="2" class="text-end">{{ __('messages.grand_total') }}</th>
                                    <th id="grand" class="text-end">{{ number_format($grandTotal, 2) }} ฿</th>
                                </tr>
                                <tr id="grand-myr-row" style="{{ ($country ?? 'TH') === 'MY' ? '' : 'display:none' }}">
                                    <th colspan="2" class="text-end">
                                        ≈ {{ __('messages.Total_MY') }}
                                        <small class="text-muted d-block">
                                            {{ __('messages.exchange rate') }} =
                                            <span id="rate-myr-note">{{ number_format($rateMyr ?? 0.13, 4) }}</span>
                                            MYR
                                        </small>
                                    </th>
                                    <th id="grand-myr" class="text-end">
                                        @if (!empty($grandMyr))
                                            {{ number_format($grandMyr, 2) }} RM
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- ===== Payment Section (QR + Slip + Note) ===== --}}
                    @php
                        // ตั้งไฟล์ QR code ตามประเทศ (เปลี่ยน path ให้ตรงกับของคุณ)
                        $qrMap = [
                            'TH' => asset('image/qr_thb.jpg'),
                            'MY' => asset('image/qr_myr.jpg'),
                        ];
                        $qrSrc = $qrMap[$country ?? 'TH'] ?? $qrMap['TH'];
                    @endphp

                    <hr class="my-0">
                    <div class="p-3 pt-4">

                        <h6 class="fw-bold mb-2">
                            {{ __('messages.scan_to_pay') ?? 'สแกนเพื่อชำระเงิน' }}
                        </h6>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <img src="{{ asset('image/qr_code.jpg') }}" alt="QR"
                                class="img-fluid rounded-3 shadow-sm mx-auto d-block" style="max-width:240px">
                            <div class="small text-muted">
                                {{-- <div>{{ __('messages.Total') ?? 'ยอดชำระ' }}: <strong
                                        id="grand-txt">{{ number_format($grandTotal, 2) }} ฿</strong></div> --}}
                                @if (($country ?? 'TH') === 'MY' && !empty($grandMyr))
                                    <div>≈ <strong>{{ number_format($grandMyr, 2) }} RM</strong></div>
                                    <div class="text-muted">({{ __('messages.exchange rate') ?? 'อัตราแปลง' }}
                                        {{ number_format($rateMyr ?? 0.13, 4) }} MYR)</div>
                                @endif
                            </div>
                        </div>

                        {{-- แนบสลิปการชำระเงิน (อยู่คอลัมน์ขวา แต่ส่งไปกับฟอร์มซ้าย) --}}
                        <div class="mt-3">
                            <label
                                class="form-label">{{ __('messages.Attach payment slip') ?? 'แนบสลิปการชำระเงิน' }}</label>
                            <div class="input-group">
                                <input type="file" name="payment_slip" id="payment_slip" class="d-none"
                                    accept=".jpg,.jpeg,.png,.pdf" form="checkout-form" required>
                                <button type="button" id="btn-choose-slip-right" class="btn btn-outline-secondary">
                                    {{ __('messages.choose_file') ?? 'เลือกไฟล์' }}
                                </button>
                                <input type="text" id="slip-filename-right" class="form-control"
                                    value="{{ __('messages.no_file_selected') ?? 'ยังไม่ได้เลือกไฟล์' }}" readonly>
                            </div>
                            <small class="text-muted">
                                {{ __('messages.Support') ?? 'รองรับ' }} .jpg .jpeg .png {{ __('messages.or') ?? 'หรือ' }}
                                .pdf
                                {{ __('messages.Size not exceeding') ?? 'ขนาดไม่เกิน' }} 4MB
                            </small>
                            @error('payment_slip')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- หมายเหตุ / เงื่อนไข --}}
                        <div class="alert alert-warning mt-3">
                            <strong>{{ __('messages.note') ?? 'หมายเหตุ' }}:</strong>
                            {{ __('messages.checkout_notice') ?? 'ลูกค้าจำเป็นต้องชำระเงินก่อน แล้วแนบสลิปการชำระเงิน จากนั้นผู้ดูแลระบบจะตรวจสอบ และหากลูกค้าต้องการยกเลิกคำสั่งซื้อ ทางร้านจะไม่มีการคืนเงินทุกกรณี' }}
                            {{-- <u>{{ __('messages.note_slip_approve') ?? 'อนุมัติคำสั่งซื้อ' }}</u> --}}
                        </div>
                    </div>


                    {{-- Submit button on the right --}}
                    <div class="card-footer bg-transparent border-0">
                        <button id="btn-submit-order" type="submit" class="btn btn-success w-100" form="checkout-form">
                            {{ __('messages.Order Confirmation') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /* 1) Prevent double-submit (ต้นฉบับ) */
            const form = document.getElementById('checkout-form');
            const submitBtn = document.getElementById('btn-submit-order');
            if (form) {
                form.addEventListener('submit', () => {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'กำลังบันทึก...';
                    }
                });
            }

            /* 2) Update shipping / totals + MYR when country changes (ต้นฉบับ) */
            const selectCountry = document.getElementById('country');
            const rowMYR = document.getElementById('grand-myr-row');
            const cellMYR = document.getElementById('grand-myr');
            const rateNote = document.getElementById('rate-myr-note');

            function updateQuote(country) {
                fetch(`{{ route('checkout.quote') }}?country=${encodeURIComponent(country)}&ts=${Date.now()}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const el = id => document.getElementById(id);
                        el('subtotal').innerText = data.subtotal + ' ฿';
                        el('shipping').innerText = data.shipping + ' ฿';
                        el('box').innerText = data.box + ' ฿';
                        el('handling').innerText = data.handling + ' ฿';
                        el('grand').innerText = data.grand + ' ฿';

                        if (data.country === 'MY' && data.grand_myr) {
                            if (rowMYR) rowMYR.style.display = '';
                            if (cellMYR) cellMYR.textContent = data.grand_myr + ' RM';
                            if (rateNote && data.rate_myr) rateNote.textContent = data.rate_myr;
                        } else {
                            if (rowMYR) rowMYR.style.display = 'none';
                        }
                    })
                    .catch(() => alert('ไม่สามารถคำนวณค่าส่งได้'));
            }

            if (selectCountry) {
                selectCountry.addEventListener('change', () => updateQuote(selectCountry.value));
            }

            /* 3) Custom file input (ต้นฉบับ) */
            // ===== Custom file input (RIGHT column) =====
            const slipInput = document.getElementById('payment_slip');
            const chooseBtn = document.getElementById('btn-choose-slip-right');
            const slipNameEl = document.getElementById('slip-filename-right');

            if (slipInput && chooseBtn && slipNameEl) {
                const showNoFile = "{{ __('messages.no_file_selected') ?? 'ยังไม่ได้เลือกไฟล์' }}";
                const openPicker = () => slipInput.click();
                chooseBtn.addEventListener('click', openPicker);
                slipNameEl.addEventListener('click', openPicker);
                slipInput.addEventListener('change', () => {
                    const name = slipInput.files && slipInput.files.length ? slipInput.files[0].name :
                        showNoFile;
                    slipNameEl.value = name;
                });
            }
            // ====== Quantity stepper ======
            const CSRF = '{{ csrf_token() }}';

            const money = (n) => Number(n || 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            const parseCell = (id) => parseFloat(String(document.getElementById(id)?.innerText || '0').replace(
                /[^\d.]/g, '') || 0);

            function recalcTotals() {
                // รวมราคาทุกแถวจาก .line-total
                let sub = 0;
                document.querySelectorAll('td.line-total').forEach(td => {
                    sub += parseFloat(String(td.innerText).replace(/[^\d.]/g, '') || 0);
                });
                const ship = parseCell('shipping');
                const box = parseCell('box');
                const hndl = parseCell('handling');

                const subEl = document.getElementById('subtotal');
                const grandEl = document.getElementById('grand');
                if (subEl) subEl.innerText = money(sub) + ' ฿';
                if (grandEl) grandEl.innerText = money(sub + ship + box + hndl) + ' ฿';
            }

            async function pushQty(updateUrl, itemId, qty) {
                if (!updateUrl) return; // fallback ถ้าไม่มี route อัปเดต
                try {
                    await fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({
                            _method: 'PATCH',
                            quantity: String(qty)
                        })
                    });
                    // ให้ server คำนวณค่าส่งใหม่ (ถ้า endpoint quote อิงตามตะกร้า)
                    const country = (document.getElementById('country')?.value) || 'TH';
                    if (typeof updateQuote === 'function') {
                        updateQuote(country);
                    }
                } catch (e) {
                    console.warn('Update cart failed', e);
                    // เก็บเงียบไว้ ใช้ค่าหน้าจอเป็นหลัก
                }
            }

            function bindStepper(step) {
                const minusBtn = step.querySelector('.minus');
                const plusBtn = step.querySelector('.plus');
                const input = step.querySelector('.qty-input');
                const maxStock = parseInt(step.dataset.stock || '1', 10);
                const price = parseFloat(step.dataset.price || '0');
                const itemId = step.dataset.itemId;
                const updateUrl = step.dataset.updateUrl;
                const hidden = document.getElementById('qty-hidden-' + itemId);
                const lineCell = step.closest('tr').querySelector('td.line-total');

                function setQty(q) {
                    let newQ = Math.max(1, Math.min(maxStock, parseInt(q || '1', 10)));
                    input.value = newQ;
                    if (hidden) hidden.value = newQ;
                    if (lineCell) {
                        lineCell.innerText = money(price * newQ) + ' ฿';
                    }
                    minusBtn.disabled = (newQ <= 1);
                    plusBtn.disabled = (newQ >= maxStock);
                    recalcTotals();
                }

                minusBtn.addEventListener('click', () => {
                    setQty(parseInt(input.value, 10) - 1);
                    pushQty(updateUrl, itemId, parseInt(input.value, 10));
                });
                plusBtn.addEventListener('click', () => {
                    setQty(parseInt(input.value, 10) + 1);
                    pushQty(updateUrl, itemId, parseInt(input.value, 10));
                });
                input.addEventListener('change', () => {
                    setQty(input.value);
                    pushQty(updateUrl, itemId, parseInt(input.value, 10));
                });

                // init state
                setQty(input.value);
            }

            document.querySelectorAll('.qty-stepper').forEach(bindStepper);
        });
    </script>

@endsection
