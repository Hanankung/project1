{{-- resources/views/member/createBooking.blade.php --}}
@extends('member.layout')

@section('content')

    <head>
        {{-- Bootstrap 5 + Bootstrap Icons --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        {{-- Flatpickr --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <style>
            /* Hero */
            .booking-hero {
                background: linear-gradient(135deg, #f8f9fa 0%, #fff 40%, #e8f5ff 100%);
                border-bottom: 1px solid #eef2f7;
                padding: 28px 0 16px;
                margin-bottom: 18px
            }

            /* Cards */
            .booking-card {
                border: 0;
                border-radius: 18px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .06)
            }

            .section-title {
                font-weight: 700;
                font-size: 1.1rem
            }

            .muted {
                color: #6c757d
            }

            .input-group .input-group-text {
                min-width: 60px
            }

            /* Summary card */
            .summary-card {
                position: sticky;
                top: 92px
            }

            .price-lg {
                font-size: 1.8rem;
                font-weight: 800;
                line-height: 1.1
            }

            .price-sub {
                font-size: .9rem;
                color: #6c757d
            }

            .divider {
                height: 1px;
                background: #eef2f7;
                margin: 12px 0
            }

            .qty-btn {
                width: 42px
            }

            /* ---- Flatpickr day styles ---- */
            .flatpickr-day {
                position: relative
            }

            /* วันเต็ม: โทนแดง + วงกลม */
            .flatpickr-day.full-day,
            .flatpickr-day.full-day:hover {
                background: #fdeaea !important;
                color: #d32f2f !important;
                border-color: #fdeaea;
            }

            .flatpickr-day.full-day::before {
                content: "";
                position: absolute;
                inset: 2px;
                border: 2px solid #d32f2f;
                border-radius: 50%;
            }

            /* มีคนจองแล้วบางส่วน: จุดสีส้มเล็กๆ */
            .flatpickr-day.partial-day::after {
                content: "";
                position: absolute;
                bottom: 4px;
                left: 50%;
                transform: translateX(-50%);
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #ffb300;
            }

            /* Legend chips (อธิบายสัญลักษณ์) */
            .legend-chip {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: 1px solid #e0e0e0;
                border-radius: 999px;
                padding: 4px 10px;
                font-size: 12px;
                margin-right: 8px
            }

            .legend-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%
            }

            .legend-dot.free {
                background: #2e7d32
            }

            .legend-dot.partial {
                background: #ffb300
            }

            .legend-dot.full {
                background: #d32f2f
            }
        </style>
    </head>

    {{-- Hero --}}
    <div class="booking-hero">
        <div class="container">
            <h2 class="mb-1">{{ __('messages.booking_form_title') }}</h2>
            <div class="muted"><i class="bi bi-calendar2-check me-1"></i>{{ __('messages.form_fill_hint') }}</div>
        </div>
    </div>

    <div class="container">
        {{-- error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php $isLocked = !empty($prefill['courseId']); @endphp

        <div class="row g-4">
            {{-- ฟอร์ม --}}
            <div class="col-lg-8">
                <div class="card booking-card">
                    <div class="card-body p-4">

                        <form action="{{ route('member.course.booking.store') }}" method="POST" class="row g-3"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($isLocked)
                                <input type="hidden" name="course_id" value="{{ $prefill['courseId'] }}">
                            @endif

                            {{-- 1) ชื่อคอร์ส --}}
                            <div class="col-12">
                                <label class="form-label section-title">
                                    <i class="bi bi-journal-text me-1"></i>{{ __('messages.course_name') }}
                                </label>
                                <input type="text" class="form-control form-control-lg" name="course_name"
                                    value="{{ old('course_name', $prefill['courseName']) }}"
                                    {{ $isLocked ? 'readonly' : '' }} required>
                            </div>

                            {{-- 2) ข้อมูลผู้จอง --}}
                            <div class="col-12 mt-2">
                                <div class="divider"></div>
                            </div>
                            <div class="col-12">
                                <div class="section-title"><i
                                        class="bi bi-person-circle me-1"></i>{{ __('messages.section_customer') }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.first_name') }}</label>
                                <input type="text" class="form-control" name="name" maxlength="20" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.last_name') }}</label>
                                <input type="text" class="form-control" name="lastname" maxlength="20" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.Phone') }}</label>
                                <input type="text" class="form-control" name="phone" maxlength="10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.email') }}</label>
                                <input type="email" class="form-control" name="email" maxlength="50" required>
                            </div>

                            {{-- 3) จำนวน & ราคา --}}
                            <div class="col-12 mt-2">
                                <div class="divider"></div>
                            </div>
                            <div class="col-12">
                                <div class="section-title"><i
                                        class="bi bi-cash-coin me-1"></i>{{ __('messages.price_details') }}</div>
                            </div>

                            {{-- จำนวนคน +/− --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('messages.people_qty') }}</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary qty-btn" type="button"
                                        id="qtyMinus">−</button>
                                    <input id="quantity" type="number" class="form-control text-center" name="quantity"
                                        min="1" max="15" value="1" required aria-describedby="qtyHelp">
                                    <button class="btn btn-outline-secondary qty-btn" type="button"
                                        id="qtyPlus">+</button>
                                </div>
                                <div id="qtyHelp" class="form-text">
                                    {{ __('messages.people_limit_15') ?? 'ไม่เกิน 15 คน' }}</div>
                            </div>

                            {{-- ราคา/คน (THB) --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('messages.price_per_person') ?? 'ราคา/คน (บาท)' }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">฿</span>
                                    <input id="unit_price_display" type="text" class="form-control" value=""
                                        readonly>
                                </div>
                                <input id="unit_price" type="hidden" name="price"
                                    value="{{ (float) ($prefill['price'] ?? 0) }}">
                            </div>

                            {{-- ราคารวม (THB) --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('messages.total_price_thb') ?? 'ราคารวม (บาท)' }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">฿</span>
                                    <input id="total_price_display" type="text" class="form-control" value=""
                                        readonly>
                                </div>
                                <input id="total_price" type="hidden" name="total_price" value="">
                            </div>

                            {{-- ราคา/คน (MYR) --}}
                            <div class="col-md-4">
                                <label
                                    class="form-label">{{ __('messages.price_per_person_myr') ?? 'ราคา/คน (MYR)' }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input id="unit_price_myr" type="text" class="form-control" value=""
                                        readonly>
                                </div>
                            </div>

                            {{-- ราคารวม (MYR) --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('messages.total_price_myr') ?? 'ราคารวม (MYR)' }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input id="total_price_myr" type="text" class="form-control" value=""
                                        readonly>
                                </div>
                                <div class="form-text">{{ __('messages.exchange_calc_note') }}</div>
                            </div>

                            {{-- วันที่จอง + สถานะที่ว่าง/เต็ม --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('messages.booking_date') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input id="booking_date" type="text" class="form-control" name="booking_date"
                                        required>
                                </div>
                                <div id="dateCapacityHelp" class="form-text mt-1"></div>
                                <div id="dateInvalidMsg" class="invalid-feedback" style="display:none"
                                    aria-live="polite">
                                    {{ __('messages.date_full') ?? 'วันที่เลือกเต็มแล้ว กรุณาเลือกวันอื่น' }}
                                </div>

                                {{-- Legend (ตัวอย่างสัญลักษณ์) --}}
                                <div class="mt-2">
                                    <span class="legend-chip"><span
                                            class="legend-dot full"></span>{{ __('messages.date_full') }}</span>
                                    <span class="legend-chip"><span
                                            class="legend-dot partial"></span>{{ __('messages.booked_some') ?? 'มีคนจองแล้ว' }}</span>
                                </div>
                            </div>

                            {{-- หมายเหตุเรื่องสลิป --}}
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        {{ __('messages.payment_note_approval') ?? 'กรุณาแนบสลิปการชำระเงินเพื่อให้แอดมินตรวจสอบและอนุมัติคำขอ' }}
                                    </div>
                                </div>
                            </div>

                            {{-- แนบสลิปการชำระเงิน --}}
                            <div class="col-lg-6">
                                <label
                                    class="form-label">{{ __('messages.payment_slip_label') ?? 'แนบสลิปการชำระเงิน' }}</label>
                                <div class="input-group">
                                    <input type="file" name="payment_slip" id="payment_slip" class="d-none"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf">
                                    <button type="button" id="btnChooseSlip" class="btn btn-outline-secondary">
                                        <i class="bi bi-paperclip me-1"></i>{{ __('messages.choose_file') }}
                                    </button>
                                    <input type="text" id="slipFilenameText" class="form-control"
                                        value="{{ __('messages.no_file_chosen') }}"
                                        data-placeholder="{{ __('messages.no_file_chosen') }}" readonly>
                                </div>
                                <div class="form-text">
                                    {{ __('messages.payment_slip_hint') ?? (__('messages.allowed_file_types') ?? 'รองรับ JPG, PNG, WEBP, PDF ขนาดไม่เกิน 4MB') }}
                                </div>
                            </div>

                            {{-- พรีวิวสลิป --}}
                            <div class="col-lg-6 d-none" id="slipPreviewWrap">
                                <label class="form-label">{{ __('messages.preview') ?? 'ตัวอย่างสลิป' }}</label>
                                <img id="slipPreview" class="img-fluid rounded border" alt="preview">
                                <div id="slipName" class="small text-muted mt-1"></div>
                            </div>

                            {{-- ปุ่มบันทึก/ย้อนกลับ --}}
                            <div class="col-12">
                                <button id="submitBtn" type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="bi bi-check2-circle me-1"></i>{{ __('messages.save_booking') }}
                                </button>
                                <a href="{{ route('member.courses') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') }}
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            {{-- สรุปราคา (Sticky) --}}
            <div class="col-lg-4">
                <div class="card booking-card summary-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-receipt-cutoff fs-4 me-2 text-primary"></i>
                            <div class="section-title m-0">{{ __('messages.summary_title') }}</div>
                        </div>
                        <div class="muted mb-2" id="summaryCourse">{{ $prefill['courseName'] ?? '' }}</div>
                        <div class="muted mb-3" id="summaryDate">
                            <i class="bi bi-calendar3 me-1"></i>{{ __('messages.summary_date_empty') }}
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('messages.people_qty') }}</span>
                            <span id="summaryQty">1</span>
                        </div>
                        <div class="divider"></div>

                        <div class="mb-1">{{ __('messages.summary_estimate') }}</div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="price-lg text-primary" id="summaryTHB">฿0.00</div>
                                <div class="price-sub">{{ __('messages.currency_thb') }}</div>
                            </div>
                            <div class="text-end">
                                <div class="price-lg" id="summaryMYR">RM 0.00</div>
                                <div class="price-sub">{{ __('messages.currency_myr') }}</div>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="muted"><i
                                class="bi bi-info-circle me-1"></i>{{ __('messages.exchange_adjustable_note') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
        @endif
    </div>

    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ms.js"></script>

    {{-- Logic: คำนวณ/เช็คโควตา/อัปเดตสรุป --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ====== DOM ======
            const btnChoose = document.getElementById('btnChooseSlip');
            const fileText = document.getElementById('slipFilenameText');
            const noFileTxt = fileText?.dataset.placeholder || 'No file chosen';
            const input = document.getElementById('payment_slip');
            const wrap = document.getElementById('slipPreviewWrap');
            const img = document.getElementById('slipPreview');
            const nameEl = document.getElementById('slipName');

            const qtyEl = document.getElementById('quantity');
            const minusBtn = document.getElementById('qtyMinus');
            const plusBtn = document.getElementById('qtyPlus');
            const qtyHelp = document.getElementById('qtyHelp');

            const unitRaw = document.getElementById('unit_price'); // number (THB/คน)
            const unitTHB = document.getElementById('unit_price_display');
            const totalTHB = document.getElementById('total_price_display');
            const totalHid = document.getElementById('total_price');

            const unitMYR = document.getElementById('unit_price_myr');
            const totalMYR = document.getElementById('total_price_myr');

            const summaryQty = document.getElementById('summaryQty');
            const summaryTHB = document.getElementById('summaryTHB');
            const summaryMYR = document.getElementById('summaryMYR');

            const bookingDate = document.getElementById('booking_date');
            const summaryDate = document.getElementById('summaryDate');
            const dateHelp = document.getElementById('dateCapacityHelp');
            const dateInvalid = document.getElementById('dateInvalidMsg');
            const submitBtn = document.getElementById('submitBtn');

            // ====== ค่าคงที่จาก PHP ======
            const RATE = {{ (float) ($rate ?? config('currency.rates.THB_MYR', 0.13)) }};
            const CAPACITY = {{ (int) ($capacity ?? 15) }};
            const BOOKED_MAP = @json($bookedMap ?? [])

            // ====== เงิน ======
            const fmtTHB = new Intl.NumberFormat('th-TH', {
                style: 'currency',
                currency: 'THB'
            });
            const fmtMYR = new Intl.NumberFormat('ms-MY', {
                style: 'currency',
                currency: 'MYR'
            });
            const unit = parseFloat(unitRaw.value || '0');

            // Helper
            const remaining = (dStr) => Math.max(0, CAPACITY - (BOOKED_MAP[dStr] || 0));
            const effectiveMax = (dStr) => {
                if (!dStr) return CAPACITY;
                const rem = remaining(dStr);
                return Math.max(1, Math.min(CAPACITY, rem));
            };
            const clamp = (val, max) => Math.max(1, Math.min(max, val));

            // ===== Flatpickr =====
            const LOCALE = @json(app()->getLocale()); // 'th'|'en'|'ms'
            const toYMD = (d) => {
                const p = (n) => String(n).padStart(2, '0');
                return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate());
            };
            const i18n = {
                remaining: @json(__('messages.remaining_seats')),
                date_full: @json(__('messages.date_full')),
            };

            const fp = flatpickr("#booking_date", {
                locale: LOCALE === 'th' ? 'th' : (LOCALE === 'ms' ? 'ms' : 'en'),
                minDate: "today",
                dateFormat: "Y-m-d", // value ที่ submit
                altInput: true,
                altFormat: "d/m/Y", // value ที่โชว์
                disable: [
                    // ปิดวันเต็ม
                    function(date) {
                        const ymd = toYMD(date);
                        return (BOOKED_MAP[ymd] || 0) >= CAPACITY;
                    }
                ],
                onDayCreate: function(_, __, inst, dayElem) {
                    const ymd = toYMD(dayElem.dateObj);
                    const booked = BOOKED_MAP[ymd] || 0;
                    dayElem.classList.remove('full-day', 'partial-day');
                    if (booked >= CAPACITY) {
                        dayElem.classList.add('full-day');
                        dayElem.title = i18n.date_full;
                    } else if (booked > 0) {
                        dayElem.classList.add('partial-day');
                        dayElem.title = `${i18n.remaining}: ${CAPACITY - booked}`;
                    }
                },
                onChange: function() {
                    bookingDate.dispatchEvent(new Event('change'));
                }
            });

            // ฟังก์ชันอัปเดตทุกอย่าง
            const update = () => {
                const dStr = bookingDate.value || '';
                const maxForDay = effectiveMax(dStr);

                // ปรับ max quantity ตามวันที่เลือก
                qtyEl.max = String(maxForDay);
                let q = clamp(parseInt(qtyEl.value || '1', 10) || 1, maxForDay);
                qtyEl.value = q;

                // ใช้ altInput ของ Flatpickr ในการใส่กรอบเขียว/แดง
                const dateVisualInput = bookingDate._flatpickr?.altInput || bookingDate;

                if (dStr) {
                    const rem = remaining(dStr);
                    if (rem <= 0) {
                        // วันเต็ม
                        dateVisualInput.classList.add('is-invalid');
                        dateVisualInput.classList.remove('is-valid');
                        dateInvalid.style.display = 'block';
                        dateHelp.textContent = '';
                        submitBtn.disabled = true;
                    } else {
                        // ยังจองได้
                        dateVisualInput.classList.remove('is-invalid');
                        dateVisualInput.classList.add('is-valid');
                        dateInvalid.style.display = 'none';
                        dateHelp.textContent = `{{ __('messages.remaining_seats') ?? 'เหลือที่ว่าง' }}: ` +
                        rem;
                        submitBtn.disabled = false;
                    }
                    qtyHelp.textContent =
                        `{{ __('messages.people_limit_dynamic') ?? 'สมัครได้สูงสุด' }} ${maxForDay} {{ __('messages.people_unit') ?? 'คน' }}`;
                } else {
                    dateVisualInput.classList.remove('is-invalid', 'is-valid');
                    dateInvalid.style.display = 'none';
                    dateHelp.textContent = '';
                    qtyHelp.textContent = `{{ __('messages.people_limit_15') ?? 'ไม่เกิน 15 คน' }}`;
                    submitBtn.disabled = false;
                }

                // คำนวณราคา
                const total = unit * q;
                const uMyr = unit * RATE;
                const tMyr = total * RATE;

                unitTHB.value = fmtTHB.format(unit);
                totalTHB.value = fmtTHB.format(total);
                totalHid.value = total.toFixed(2);

                unitMYR.value = fmtMYR.format(uMyr);
                totalMYR.value = fmtMYR.format(tMyr);

                summaryQty.textContent = q;
                summaryTHB.textContent = fmtTHB.format(total);
                summaryMYR.textContent = fmtMYR.format(tMyr);
            };

            // +/−
            minusBtn.addEventListener('click', () => {
                qtyEl.value = Math.max(1, (parseInt(qtyEl.value || '1', 10) || 1) - 1);
                update();
            });
            plusBtn.addEventListener('click', () => {
                qtyEl.value = (parseInt(qtyEl.value || '1', 10) || 1) + 1;
                update();
            });
            qtyEl.addEventListener('input', update);

            // วันที่ + สรุปวันที่
            bookingDate.addEventListener('change', () => {
                if (bookingDate.value) {
                    summaryDate.innerHTML = '<i class="bi bi-calendar3 me-1"></i>' + bookingDate.value;
                } else {
                    summaryDate.innerHTML =
                        '<i class="bi bi-calendar3 me-1"></i>{{ __('messages.summary_date_empty') }}';
                }
                update();
            });

            // สลิป: เปิด file picker
            btnChoose?.addEventListener('click', () => input?.click());

            // ไฟล์/พรีวิว
            input?.addEventListener('change', () => {
                const f = input.files?.[0];
                fileText.value = f ? f.name : noFileTxt;
                if (!f) {
                    wrap?.classList.add('d-none');
                    return;
                }

                const okTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
                if (!okTypes.includes(f.type)) {
                    alert(
                        '{{ __('messages.file_type_invalid') ?? 'ไฟล์ไม่ถูกชนิด (อนุญาต JPG, PNG, WEBP หรือ PDF)' }}');
                    input.value = '';
                    fileText.value = noFileTxt;
                    wrap?.classList.add('d-none');
                    return;
                }
                if (f.size > 4 * 1024 * 1024) {
                    alert('{{ __('messages.file_too_large') ?? 'ไฟล์ใหญ่เกิน 4MB' }}');
                    input.value = '';
                    fileText.value = noFileTxt;
                    wrap?.classList.add('d-none');
                    return;
                }
                if (f.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        img.src = e.target.result;
                        wrap.classList.remove('d-none');
                    };
                    reader.readAsDataURL(f);
                } else {
                    wrap?.classList.add('d-none');
                }
                if (nameEl) nameEl.textContent = f.name;
            });

            update(); // init
        });
    </script>
@endsection
