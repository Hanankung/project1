{{-- resources/views/member/course_detail.blade.php --}}
@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <div class="container py-5">

        {{-- ===== เตรียมตัวแยกหัวข้อ (SMART FORMATTER v2) ===== --}}
        @php
            $locale = app()->getLocale();

            // เลือกฟิลด์รายละเอียดตามภาษา (เผื่อยังไม่ได้ map ไว้ที่ detail_i18n)
            $detailField = $locale === 'en'
                ? 'course_detail_ENG'
                : ($locale === 'ms' ? 'course_detail_MS' : 'course_detail');

            $raw = trim((string) ($course->detail_i18n ?? ($course->$detailField ?? '')));

            // ป้ายหัวข้อที่ผู้สอนพิมพ์ไว้ในข้อความ (ไม่ต้องแก้ DB)
            $labels = [
                'th' => ['learn' => 'สิ่งที่จะได้เรียนรู้', 'equip' => 'อุปกรณ์ที่มีให้'],
                'en' => ['learn' => 'What you will learn', 'equip' => 'Equipment provided'],
                'ms' => ['learn' => 'Apa yang anda akan pelajari', 'equip' => 'Peralatan yang disediakan'],
            ][$locale] ?? ['learn' => 'สิ่งที่จะได้เรียนรู้', 'equip' => 'อุปกรณ์ที่มีให้'];

            $labelLearn = $labels['learn'];
            $labelEquip = $labels['equip'];

            // ตัดตอนระหว่าง label เริ่มต้น -> ก่อน label ถัดไป (หรือจบ)
            $sliceBetween = function (string $txt, string $startLabel, array $endLabels) {
                $sPos = mb_stripos($txt, $startLabel);
                if ($sPos === false) return null;

                $start = $sPos + mb_strlen($startLabel);
                $after = mb_substr($txt, $start);
                $after = preg_replace('/^\s*[:：]?\s*/u', '', $after); // ตัด ":" และช่องว่างต้นบรรทัด

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

            // แปลงเนื้อหาเป็นรายการ bullet (รองรับ emoji/สัญลักษณ์เป็นตัวแบ่ง)
            $toList = function (?string $txt) {
                if (!$txt) return [];

                $txt = str_replace(["\r\n", "\r"], "\n", $txt);
                // แทน bullet/emoji/สัญลักษณ์ด้วยขึ้นบรรทัด
                $txt = preg_replace(
                    '/[•●▪▫‣·◦\-–—→]|🕘|💰|🍽️|👉|✨|✅|🔸|🔹|💡|🟠|🟢|🍊|🍃|🤲|📚|🧵|🧶|✂️|🧪|🌿|🌱|📌|⭐/u',
                    "\n",
                    $txt
                );
                // แตกบรรทัด
                $lines = preg_split('/\n+/u', $txt);
                $out = [];
                foreach ($lines as $line) {
                    $line = trim(preg_replace('/^[\-\–\—•●▪▫‣·◦]+/u', '', $line)); // ล้าง bullet นำหน้า
                    if ($line === '') continue;

                    // กันกรณีพิมพ์ชื่อหัวข้อซ้ำ
                    if (
                        mb_stripos($line, 'สิ่งที่จะได้เรียนรู้') === 0 ||
                        mb_stripos($line, 'อุปกรณ์ที่มีให้') === 0 ||
                        mb_stripos($line, 'What you will learn') === 0 ||
                        mb_stripos($line, 'Equipment provided') === 0 ||
                        mb_stripos($line, 'Apa yang anda akan pelajari') === 0 ||
                        mb_stripos($line, 'Peralatan yang disediakan') === 0
                    ) {
                        continue;
                    }

                    $out[] = $line;
                }
                return $out;
            };

            // meta: ส่วนก่อนหัวข้อแรก (เช่น เวลา/ราคา/สวัสดิการ)
            $firstPos = min(
                array_filter(
                    [
                        mb_stripos($raw, $labels['learn']) !== false ? mb_stripos($raw, $labels['learn']) : null,
                        mb_stripos($raw, $labels['equip']) !== false ? mb_stripos($raw, $labels['equip']) : null,
                    ],
                    fn ($v) => $v !== null
                )
            );
            $metaText = $firstPos !== false && $firstPos !== null ? trim(mb_substr($raw, 0, $firstPos)) : '';

            // เนื้อหาหัวข้อหลัก
            $learnText = $sliceBetween($raw, $labels['learn'], [$labels['equip']]);
            $equipText = $sliceBetween($raw, $labels['equip'], [$labels['learn']]);

            // ถ้าไม่เจอหัวข้อเลย → ยัดทั้งหมดไปที่ "สิ่งที่จะได้เรียนรู้"
            if (!$learnText && !$equipText) {
                $learnText = $raw;
            }

            $metaList  = $toList($metaText);
            $learnList = $toList($learnText);
            $equipList = $toList($equipText);
        @endphp
        {{-- ===== /เตรียม formatter ===== --}}

        {{-- แถวบน: รูป (ซ้าย) + ชื่อ/ราคา/ปุ่ม (ขวา) --}}
        <div class="row">
            {{-- รูปหลัก --}}
            <div class="col-md-6 d-flex justify-content-center">
                @if ($course->course_image)
                    <img src="{{ asset($course->course_image) }}" class="rounded shadow" alt="{{ $course->course_name }}"
                         style="width:300px; height:300px; object-fit:cover;">
                @else
                    <img src="https://via.placeholder.com/300x300?text=No+Image" class="rounded shadow" alt="No Image"
                         style="width:300px; height:300px; object-fit:cover;">
                @endif
            </div>

            {{-- คอลัมน์ขวา: หัวเรื่อง + ราคา + ปุ่ม + meta --}}
            <div class="col-md-6">
                <h2 class="mb-2">{{ $course->name_i18n }}</h2>
                <h4 class="text-primary mb-3">
                    {{ __('messages.price') }}: {{ number_format($course->price) }} {{ __('messages.baht') }}
                </h4>

                {{-- ปุ่มของสมาชิก: ไปหน้าจองคอร์ส + ปุ่มย้อนกลับ --}}
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('member.course.booking', $course->id) }}" class="btn btn-success">{{ __('messages.book_course') }}</a>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>

                {{-- meta ใต้หัวเรื่อง (บรรทัดสั้น ๆ) --}}
                @if (count($metaList))
                    <div class="text-muted small">
                        {{ implode(' • ', $metaList) }}
                    </div>
                @endif
            </div>
        </div> {{-- /row --}}

        {{-- การ์ด 2 คอลัมน์: สิ่งที่จะได้เรียนรู้ / อุปกรณ์ที่มีให้ --}}
        <div class="card p-3 mt-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <h5 class="mb-2">{{ $labelLearn }}</h5>
                    @if (count($learnList))
                        <ul class="mb-0">
                            @foreach ($learnList as $li)
                                <li>{{ $li }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-0" style="white-space:pre-line">{{ $learnText }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="mb-2">{{ $labelEquip }}</h5>
                    @if (count($equipList))
                        <ul class="mb-0">
                            @foreach ($equipList as $li)
                                <li>{{ $li }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">-</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- แกลเลอรี --}}
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="fw-bold mb-4">{{ __('messages.gallery') }} :</h4>
            </div>

            <div class="row row-cols-2 row-cols-md-5 g-3">
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail7.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 1">
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail1.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 2">
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail2.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 3">
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail3.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 4">
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('image/course_detail6.jpg') }}" class="card-img-top rounded" alt="กิจกรรม 5">
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
