@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

                            {{-- Note --}}
                            <div class="alert alert-warning mt-3">
                                <strong>{{ __('messages.note') }}:</strong>
                                {{ __('messages.note_slip') }}
                                <u>{{ __('messages.note_slip_approve') }}</u>
                            </div>

                            {{-- Payment slip (custom UI) --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.Attach payment slip') }}</label>

                                <div class="input-group">
                                    <input type="file" name="payment_slip" id="payment_slip" class="d-none"
                                        accept=".jpg,.jpeg,.png,.pdf" required>

                                    <button type="button" id="btn-choose-slip" class="btn btn-outline-secondary">
                                        {{ __('messages.choose_file') }}
                                    </button>

                                    <input type="text" id="slip-filename" class="form-control"
                                        value="{{ __('messages.no_file_selected') }}" readonly>
                                </div>

                                <small class="text-muted">
                                    {{ __('messages.Support') }} .jpg .jpeg .png {{ __('messages.or') }} .pdf
                                    {{ __('messages.Size not exceeding') }} 4MB
                                </small>

                                @error('payment_slip')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ไม่มีปุ่ม submit ที่ฝั่งซ้ายแล้ว --}}
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
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->product->price * $item->quantity, 2) }} ฿
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
            /* 1) Prevent double-submit */
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

            /* 2) Update shipping / totals + MYR when country changes */
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

            /* 3) Custom file input (multilang labels) */
            const slipInput = document.getElementById('payment_slip');
            const chooseBtn = document.getElementById('btn-choose-slip');
            const slipNameEl = document.getElementById('slip-filename');

            if (slipInput && chooseBtn && slipNameEl) {
                const showNoFile = "{{ __('messages.no_file_selected') }}";
                const openPicker = () => slipInput.click();

                chooseBtn.addEventListener('click', openPicker);
                slipNameEl.addEventListener('click', openPicker);

                slipInput.addEventListener('change', () => {
                    const name = slipInput.files && slipInput.files.length ? slipInput.files[0].name :
                        showNoFile;
                    slipNameEl.value = name;
                });
            }
        });
    </script>

@endsection
