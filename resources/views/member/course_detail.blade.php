{{-- resources/views/member/course_detail.blade.php --}}
@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .course-hero {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 40%, #e8f5ff 100%);
                border-bottom: 1px solid #eef2f7;
                padding: 28px 0 16px;
                margin-bottom: 18px;
            }

            .img-frame {
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 12px 28px rgba(0, 0, 0, .12);
                max-width: 520px;
                /* ⬅️ ย่อขนาดรูปหลัก */
                margin: 0 auto;
                /* จัดกึ่งกลางในคอลัมน์ */
            }

            .price-card {
                border: 1px solid #e9ecef;
                border-radius: 14px;
                padding: 14px 16px;
                display: inline-block;
                background: #fff;
                box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
            }

            .price-main {
                font-size: 1.9rem;
                font-weight: 800;
                color: #0d6efd;
                line-height: 1;
            }

            .price-sub {
                color: #6c757d;
                margin-top: 2px;
            }

            .chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                border: 1px solid #e9ecef;
                background: #fff;
                margin: 6px 8px 0 0;
                font-size: .95rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, .04);
            }

            .section-card {
                border: 0;
                border-radius: 18px;
                box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
            }

            .section-title {
                font-weight: 700;
                font-size: 1.15rem;
            }

            .ul-clean {
                padding-left: 0;
                margin: 0;
                list-style: none;
            }

            .ul-clean li {
                margin: .35rem 0;
            }

            .gallery .card {
                border: 0;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 6px 16px rgba(0, 0, 0, .08);
                cursor: pointer;
            }

            .gallery img {
                object-fit: cover;
                height: 160px;
                width: 100%;
            }
        </style>
    </head>

    @php
        $locale = app()->getLocale();

        // เลือก field รายละเอียดตามภาษา
        $detailField =
            $locale === 'en' ? 'course_detail_ENG' : ($locale === 'ms' ? 'course_detail_MS' : 'course_detail');

        $raw = trim((string) ($course->detail_i18n ?? ($course->$detailField ?? '')));

        // ป้ายหัวข้อที่ผู้สอนพิมพ์ไว้
        $labels = [
            'th' => ['learn' => 'สิ่งที่จะได้เรียนรู้', 'equip' => 'อุปกรณ์ที่มีให้'],
            'en' => ['learn' => 'What you will learn', 'equip' => 'Equipment provided'],
            'ms' => ['learn' => 'Apa yang anda akan pelajari', 'equip' => 'Peralatan yang disediakan'],
        ][$locale] ?? ['learn' => 'สิ่งที่จะได้เรียนรู้', 'equip' => 'อุปกรณ์ที่มีให้'];

        $labelLearn = $labels['learn'];
        $labelEquip = $labels['equip'];

        // helper: ดึงข้อความระหว่างหัวข้อ
        $sliceBetween = function (string $txt, string $startLabel, array $endLabels) {
            $sPos = mb_stripos($txt, $startLabel);
            if ($sPos === false) {
                return null;
            }
            $start = $sPos + mb_strlen($startLabel);
            $after = mb_substr($txt, $start);
            $after = preg_replace('/^\s*[:：]?\s*/u', '', $after);
            $min = null;
            foreach ($endLabels as $lab) {
                $p = mb_stripos($after, $lab);
                if ($p !== false) {
                    $min = is_null($min) ? $p : min($min, $p);
                }
            }
            $content = is_null($min) ? $after : mb_substr($after, 0, $min);
            return trim($content ?? '');
        };

        // helper: แปลงข้อความยาว -> bullet list
        $toList = function (?string $txt) {
            if (!$txt) {
                return [];
            }
            $txt = str_replace(["\r\n", "\r"], "\n", $txt);
            $txt = preg_replace(
                '/[•●▪▫‣·◦\-–—→]|🕘|💰|🍽️|👉|✨|✅|🔸|🔹|💡|🟠|🟢|🍊|🍃|🤲|📚|🧵|🧶|✂️|🧪|🌿|🌱|📌|⭐/u',
                "\n",
                $txt,
            );
            $lines = preg_split('/\n+/u', $txt);
            $out = [];
            foreach ($lines as $line) {
                $line = trim(preg_replace('/^[\-\–\—•●▪▫‣·◦]+/u', '', $line));
                if ($line === '') {
                    continue;
                }
                if (
                    preg_match(
                        '/^(สิ่งที่จะได้เรียนรู้|อุปกรณ์ที่มีให้|What you will learn|Equipment provided|Apa yang anda akan pelajari|Peralatan yang disediakan)/u',
                        $line,
                    )
                ) {
                    continue;
                }
                $out[] = $line;
            }
            return $out;
        };

        // meta (ส่วนก่อนหัวข้อ)
        $firstPos = min(
            array_filter(
                [
                    mb_stripos($raw, $labels['learn']) !== false ? mb_stripos($raw, $labels['learn']) : null,
                    mb_stripos($raw, $labels['equip']) !== false ? mb_stripos($raw, $labels['equip']) : null,
                ],
                fn($v) => $v !== null,
            ),
        );
        $metaText = $firstPos !== false && $firstPos !== null ? trim(mb_substr($raw, 0, $firstPos)) : '';

        // สกัดแต่ละส่วน
        $learnText = $sliceBetween($raw, $labels['learn'], [$labels['equip']]);
        $equipText = $sliceBetween($raw, $labels['equip'], [$labels['learn']]);
        if (!$learnText && !$equipText) {
            $learnText = $raw;
        }

        $learnList = $toList($learnText);
        $equipList = $toList($equipText);

        // ราคา THB -> MYR
        $rate = (float) config('currency.rates.THB_MYR', 0.13);
        $priceTHB = (float) ($course->price ?? 0);
        $priceMYR = $priceTHB * $rate;
    @endphp

    {{-- HERO --}}
    <div class="course-hero">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-journal-text text-primary fs-3"></i>
                <div>
                    <h3 class="mb-0">{{ $course->name_i18n }}</h3>
                    <div class="text-muted small">{{ __('messages.gallery') }} • {{ count($learnList) + count($equipList) }}
                        items</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-4">
        <div class="row g-4 align-items-start">
            {{-- ซ้าย: รูปหลัก (ย่อขนาด) --}}
            <div class="col-lg-5">
                <div class="img-frame">
                    @if ($course->course_image)
                        <img src="{{ asset($course->course_image) }}" alt="{{ $course->course_name }}" class="w-100"
                            style="object-fit:cover; aspect-ratio: 4/3;">
                    @else
                        <img src="https://via.placeholder.com/800x600?text=No+Image" class="w-100"
                            style="object-fit:cover; aspect-ratio: 4/3;">
                    @endif
                </div>
            </div>

            {{-- ขวา: ราคา + CTA + chips คงที่ตามที่ต้องการ --}}
            <div class="col-lg-7">
                <div class="price-card mb-3">
                    <div class="price-main">฿{{ number_format($priceTHB, 2) }}</div>
                    <div class="price-sub">≈ RM {{ number_format($priceMYR, 2) }}</div>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('member.course.booking', $course->id) }}" class="btn btn-success btn-lg">
                        <i class="bi bi-calendar2-check me-1"></i>{{ __('messages.book_course') }}
                    </a>
                    <a href="{{ route('member.courses') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
                    </a>
                </div>

                {{-- ⬇️ Chips ข้อความตามที่ผู้ใช้ระบุ --}}
                <div class="mb-2">
                    <span class="chip">
                        <i class="bi bi-check2-circle text-success"></i>
                        {{ __('messages.class_time_template', ['start' => '09:00', 'end' => '15:00', 'hours' => 6]) }}
                    </span>
                    <span class="chip">
                        <i class="bi bi-check2-circle text-success"></i>
                        {{ __('messages.cost') }}: {{ number_format($priceTHB, 0) }} {{ __('messages.baht_per_person') }}
                    </span>
                    <span class="chip">
                        <i class="bi bi-check2-circle text-success"></i>
                        {{ __('messages.course_meal_note') }}
                    </span>
                    <span class="chip">
                        <i class="bi bi-check2-circle text-success"></i>
                        {{ __('messages.take_home_artwork', ['count' => 1]) }}
                    </span>
                </div>

                {{-- ช่องทางติดต่อ --}}
                <div class="card border-0 shadow-sm p-3 mt-3">
                    <div class="section-title mb-1"><i class="bi bi-headset me-1 text-primary"></i>{{ __('messages.contact_channel') }}</div>
                    <div class="text-muted">by Siro-Secret</div>
                    <div class="mt-1"><i class="bi bi-telephone me-1"></i>097-3502899</div>
                </div>
            </div>
        </div>

        {{-- สิ่งที่จะได้เรียนรู้ / อุปกรณ์ที่มีให้ --}}
        <div class="card section-card p-4 mt-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="section-title mb-2"><i
                            class="bi bi-mortarboard me-1 text-primary"></i>{{ $labels['learn'] }}</div>
                    @if (count($learnList))
                        <ul class="ul-clean">
                            @foreach ($learnList as $li)
                                <li><i class="bi bi-check2-circle text-success me-2"></i>{{ $li }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-0" style="white-space:pre-line">{{ $learnText }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="section-title mb-2"><i class="bi bi-box-seam me-1 text-primary"></i>{{ $labels['equip'] }}
                    </div>
                    @if (count($equipList))
                        <ul class="ul-clean">
                            @foreach ($equipList as $li)
                                <li><i class="bi bi-dot text-primary me-1"></i>{{ $li }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">-</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- แกลเลอรี --}}
        <div class="mt-5">
            <h5 class="fw-bold mb-3"><i class="bi bi-images me-1 text-primary"></i>{{ __('messages.gallery') }}</h5>
            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3 gallery">
                @foreach (['image/course_detail7.jpg', 'image/course_detail1.jpg', 'image/course_detail2.jpg', 'image/course_detail3.jpg', 'image/course_detail6.jpg'] as $gimg)
                    <div class="col">
                        <div class="card">
                            <img src="{{ asset($gimg) }}" alt="gallery" class="lightbox">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal แสดงภาพใหญ่ --}}
    <div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="imgModalSrc" src="" class="w-100" style="object-fit:contain; max-height:80vh;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.back') }}</button>
                </div>
                <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS (ถ้ายังไม่มีใน layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // lightbox: คลิกรูปเพื่อเปิด modal
        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('imgModal');
            const modalImg = document.getElementById('imgModalSrc');
            const bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;

            document.querySelectorAll('.lightbox').forEach(img => {
                img.addEventListener('click', () => {
                    if (!bsModal) return;
                    modalImg.src = img.src;
                    bsModal.show();
                });
            });
        });
    </script>
@endsection
