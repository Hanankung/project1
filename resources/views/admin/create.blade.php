{{-- resources/views/admin/create.blade.php --}}
@extends('admin.layout')

@section('content')
    <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <style>
            :root {
                --ink: #1f2937;
                --muted: #6b7280;
                --paper: #faf7f2;
                --brand: #6b4e2e;
                --brand-2: #8a6b47;
                --danger: #e03131;
            }

            .page-hero {
                background: linear-gradient(135deg, #fff 0%, var(--paper) 100%);
                border: 1px solid #eee;
                border-radius: 16px;
                padding: 18px 20px;
                margin-bottom: 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
            }

            .form-card {
                border: 1px solid #eee;
                border-radius: 16px;
                background: #fff;
                box-shadow: 0 8px 22px rgba(0, 0, 0, .04);
            }

            .form-card .card-header {
                background: #fff;
                border-bottom: 1px solid #f0f0f0;
                padding: 14px 18px;
            }

            .form-card .card-body {
                padding: 18px;
            }

            .req::after {
                content: " *";
                color: var(--danger);
                font-weight: 700
            }

            .muted,
            .help {
                color: var(--muted);
            }

            .help {
                font-size: .85rem
            }

            .thumb {
                width: 100%;
                aspect-ratio: 4/3;
                object-fit: cover;
                border: 1px dashed #ddd;
                border-radius: 12px;
                background: #fafafa;
                display: block;
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

            .btn-brand {
                background: linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 10px 16px;
                font-weight: 600;
                box-shadow: 0 6px 16px rgba(139, 92, 45, .25);
            }

            .btn-brand:hover {
                filter: brightness(1.05);
                color: #fff
            }

            .count {
                font-variant-numeric: tabular-nums;
            }

            .lang-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 6px 12px;
                border-radius: 999px;
                background: #eef2ff;
                color: #1e3a8a;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .section-sep {
                height: 1px;
                background: #f1f1f1;
                margin: 14px 0;
            }
        </style>

        {{-- HERO --}}
        <div class="page-hero d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">เพิ่มสินค้า</h1>
                <span class="badge text-bg-light">กรอกข้อมูลให้ครบใน 3 ส่วน: รายละเอียด 3 ภาษา | คลัง/ราคา | รูปภาพ</span>
            </div>
            <a href="{{ route('admin.product') }}" class="btn btn-outline-secondary">กลับไปหน้าสินค้า</a>
        </div>

        <div class="row g-3">
            {{-- LEFT: รายละเอียด 3 ภาษา (แบบต่อยาว) --}}
            <div class="col-12 col-lg-8">
                <div class="card form-card">
                    <div class="card-header"><strong>รายละเอียดสินค้า (3 ภาษา)</strong></div>
                    <div class="card-body">

                        {{-- THAI --}}
                        <div class="lang-chip">ไทย</div>
                        <div class="mb-3">
                            <label class="form-label req">ชื่อสินค้า</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                name="product_name" value="{{ old('product_name') }}" placeholder="ชื่อสินค้า" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียดสินค้า</label>
                            <textarea class="form-control countable" name="description" rows="4" data-max="600"
                                placeholder="รายละเอียดสินค้า">{{ old('description') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">600</span> ตัวอักษร</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">วัสดุ</label>
                                <input type="text" class="form-control" name="material" value="{{ old('material') }}"
                                    placeholder="เช่น ผ้าฝ้ายธรรมชาติ">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ขนาด</label>
                                <input type="text" class="form-control" name="size" value="{{ old('size') }}"
                                    placeholder="เช่น 15 × 20 cm">
                            </div>
                        </div>

                        <div class="section-sep"></div>

                        {{-- ENGLISH --}}
                        <div class="lang-chip">English</div>
                        <div class="mb-3">
                            <label class="form-label">Product name (English)</label>
                            <input type="text" class="form-control" name="product_name_ENG"
                                value="{{ old('product_name_ENG') }}" placeholder="Product name in English">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (English)</label>
                            <textarea class="form-control countable" name="description_ENG" rows="4" data-max="600">{{ old('description_ENG') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">600</span> chars</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Material (English)</label>
                                <input type="text" class="form-control" name="material_ENG"
                                    value="{{ old('material_ENG') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Size (EN)</label>
                                <input type="text" class="form-control" name="size_en_fake" value=""
                                    placeholder="Optional (uses TH if empty)" disabled>
                                <div class="help">ใช้ค่าจากภาษาไทย หากไม่ได้กำหนด</div>
                            </div>
                        </div>

                        <div class="section-sep"></div>

                        {{-- MALAY --}}
                        <div class="lang-chip">Malay</div>
                        <div class="mb-3">
                            <label class="form-label">Product name (Malay)</label>
                            <input type="text" class="form-control" name="product_name_MS"
                                value="{{ old('product_name_MS') }}" placeholder="Product name in Malay">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Malay)</label>
                            <textarea class="form-control countable" name="description_MS" rows="4" data-max="600">{{ old('description_MS') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">600</span> aksara</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Material (Malay)</label>
                                <input type="text" class="form-control" name="material_MS"
                                    value="{{ old('material_MS') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Size (MS)</label>
                                <input type="text" class="form-control" name="size_ms_fake" value=""
                                    placeholder="Optional" disabled>
                                <div class="help">ใช้ค่าจากภาษาไทย หากไม่ได้กำหนด</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT: ราคา/สต็อก + รูปภาพ --}}
            <div class="col-12 col-lg-4">
                <div class="card form-card mb-3">
                    <div class="card-header"><strong>ราคา & คลังสินค้า</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label req">ราคา</label>
                            <div class="input-group">
                                <span class="input-group-text">฿</span>
                                <input type="number" step="0.01" min="0"
                                    class="form-control @error('price') is-invalid @enderror" name="price"
                                    value="{{ old('price') }}" placeholder="0.00" required>
                                <span class="input-group-text">ต่อชิ้น</span>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label req">จำนวนสินค้า</label>
                                <input type="number" min="0"
                                    class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                    value="{{ old('quantity') }}" placeholder="0" required>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">เกณฑ์แจ้งเตือน (ชิ้น)</label>
                                <input type="number" min="0" class="form-control" name="low_stock_threshold"
                                    value="{{ old('low_stock_threshold', 5) }}">
                                <div class="help">แจ้งเตือน “ใกล้หมด” เมื่อคงเหลือน้อยกว่าหรือเท่าค่านี้</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card form-card">
                    <div class="card-header"><strong>รูปภาพสินค้า</strong></div>
                    <div class="card-body">
                        <div class="mb-2"><img id="preview" class="thumb" alt="Preview"></div>
                        <div class="mb-2">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="help">แนะนำสัดส่วน 4:3 หรือ 16:9 • รองรับ JPG/PNG • ขนาด &lt; 3MB</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="autoCrop" checked>
                            <label class="form-check-label" for="autoCrop">แสดงพรีวิวแบบครอปพอดีกรอบ</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Actions --}}
        <div class="sticky-actions">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.product') }}" class="btn btn-outline-secondary">ยกเลิก</a>
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-check2-circle"></i> บันทึกสินค้า
                </button>
            </div>
        </div>
    </form>

    {{-- Scripts: preview + counters --}}
    <script>
        // image preview
        const imgInput = document.getElementById('image');
        const preview = document.getElementById('preview');
        const autoCrop = document.getElementById('autoCrop');

        function setPreviewObjectFit() {
            preview.style.objectFit = autoCrop?.checked ? 'cover' : 'contain';
            preview.style.background = autoCrop?.checked ? '#fafafa' : '#fff';
        }
        setPreviewObjectFit();
        autoCrop?.addEventListener('change', setPreviewObjectFit);
        imgInput?.addEventListener('change', e => {
            const [file] = e.target.files || [];
            preview.src = file ? URL.createObjectURL(file) : '';
        });

        // live counters for textareas with data-max
        document.querySelectorAll('.countable').forEach(el => {
            const wrap = el.parentElement;
            const count = wrap.querySelector('.count');
            const maxEl = wrap.querySelector('.max');
            const max = parseInt(el.dataset.max || '600', 10);
            maxEl.textContent = max;
            const update = () => {
                const len = el.value.length;
                count.textContent = len;
                count.style.color = len > max ? '#e03131' : '#6b7280';
            };
            el.addEventListener('input', update);
            update();
        });
    </script>
@endsection
