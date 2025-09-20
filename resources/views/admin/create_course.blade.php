{{-- resources/views/admin/course_create.blade.php --}}
@extends('admin.layout')

@section('content')
    <form action="{{ route('store_course') }}" method="POST" enctype="multipart/form-data" id="courseForm">
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
                color: var(--muted)
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
                <h1 class="m-0" style="font-weight:800; letter-spacing:.2px; color:var(--ink)">คอร์สเรียน</h1>
                <span class="badge text-bg-light">กรอกข้อมูลให้ครบ: รายละเอียด 3 ภาษา | ราคา | รูปภาพ</span>
            </div>
            <a href="{{ route('admin.course') }}" class="btn btn-outline-secondary">กลับไปหน้าคอร์ส</a>
        </div>

        <div class="row g-3">
            {{-- LEFT: รายละเอียด 3 ภาษา (ต่อยาว) --}}
            <div class="col-12 col-lg-8">
                <div class="card form-card">
                    <div class="card-header"><strong>รายละเอียดคอร์ส (3 ภาษา)</strong></div>
                    <div class="card-body">

                        {{-- THAI --}}
                        <div class="lang-chip">ไทย</div>
                        <div class="mb-3">
                            <label class="form-label req">ชื่อคอร์สเรียน</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror"
                                id="course_name" name="course_name" value="{{ old('course_name') }}"
                                placeholder="เช่น คอร์ส Eco Print สำหรับผู้เริ่มต้น" required>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียดคอร์ส</label>
                            <textarea class="form-control countable" id="course_detail" name="course_detail" rows="4" data-max="800"
                                placeholder="ภาพรวมสิ่งที่จะได้เรียนรู้ อุปกรณ์ที่มีให้ ฯลฯ">{{ old('course_detail') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">800</span> ตัวอักษร</div>
                        </div>

                        <div class="section-sep"></div>

                        {{-- ENGLISH --}}
                        <div class="lang-chip">English</div>
                        <div class="mb-3">
                            <label class="form-label">Course name (English)</label>
                            <input type="text" class="form-control" id="course_name_ENG" name="course_name_ENG"
                                value="{{ old('course_name_ENG') }}" placeholder="Course name in English">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course details (English)</label>
                            <textarea class="form-control countable" id="course_detail_ENG" name="course_detail_ENG" rows="4" data-max="800">{{ old('course_detail_ENG') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">800</span> chars</div>
                        </div>

                        <div class="section-sep"></div>

                        {{-- MALAY --}}
                        <div class="lang-chip">Malay</div>
                        <div class="mb-3">
                            <label class="form-label">Course name (Malay)</label>
                            <input type="text" class="form-control" id="course_name_MS" name="course_name_MS"
                                value="{{ old('course_name_MS') }}" placeholder="Course name in Malay">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course details (Malay)</label>
                            <textarea class="form-control countable" id="course_detail_MS" name="course_detail_MS" rows="4" data-max="800">{{ old('course_detail_MS') }}</textarea>
                            <div class="help"><span class="count">0</span>/<span class="max">800</span> aksara</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT: ราคา + รูปภาพ --}}
            <div class="col-12 col-lg-4">
                <div class="card form-card mb-3">
                    <div class="card-header"><strong>ราคา</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label req">ราคา</label>
                            <div class="input-group">
                                <span class="input-group-text">฿</span>
                                <input type="number" step="0.01" min="0"
                                    class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                    value="{{ old('price') }}" placeholder="0.00" required>
                                <span class="input-group-text">ต่อคน</span>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card form-card">
                    <div class="card-header"><strong>รูปภาพคอร์ส</strong></div>
                    <div class="card-body">
                        <div class="mb-2">
                            <img id="preview" class="thumb" alt="Preview">
                        </div>
                        <div class="mb-2">
                            <input type="file" class="form-control" id="course_image" name="course_image"
                                accept="image/*">
                            <div class="help">แนะนำสัดส่วน 4:3 หรือ 16:9 • JPG/PNG • ขนาด &lt; 3MB</div>
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
                <a href="{{ route('admin.course') }}" class="btn btn-outline-secondary">ยกเลิก</a>
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-check2-circle"></i> บันทึกคอร์ส
                </button>
            </div>
        </div>
    </form>

    {{-- Scripts: image preview + counters --}}
    <script>
        // image preview
        const imgInput = document.getElementById('course_image');
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
            const max = parseInt(el.dataset.max || '800', 10);
            if (maxEl) maxEl.textContent = max;
            const update = () => {
                const len = el.value.length;
                if (count) count.textContent = len;
                if (count) count.style.color = len > max ? '#e03131' : '#6b7280';
            };
            el.addEventListener('input', update);
            update();
        });
    </script>
@endsection
