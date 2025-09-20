@php
  use Carbon\Carbon;
  $b = $booking ?? null;
  $approved = $approved ?? false;
  $id   = $b->id ?? '';
  $date = !empty($b->booking_date) ? Carbon::parse($b->booking_date)->timezone('Asia/Bangkok')->format('d/m/Y') : '';
  $qty  = $b->quantity ?? 1;
  $sum  = isset($b->total_price) ? number_format($b->total_price, 2) : '0.00';
  $course = $b->course_name ?? 'Eco Print Course';
  $detailUrl = $detailUrl ?? url('/');
@endphp
<!doctype html><html><head><meta charset="utf-8"><style>
  .wrap{max-width:640px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:10px}
  .box{padding:24px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Prompt,Arial,sans-serif}
  h2{margin:0 0 10px;font-size:20px;color:#111827}
  p,td{font-size:14px;line-height:1.6;color:#374151;margin:0}
  .muted{color:#6b7280}.hr{height:1px;background:#e5e7eb;margin:18px 0}
  .btn{display:inline-block;text-decoration:none;padding:10px 16px;border-radius:8px;background:#111827;color:#fff;font-weight:600}
  .tag{display:inline-block;background:#f3f4f6;color:#111827;font-size:12px;padding:2px 8px;border-radius:999px;margin-bottom:6px}
  table{width:100%;border-collapse:collapse;margin-top:8px} td{padding:6px 0;border-bottom:1px solid #f3f4f6}
  .label{width:40%}
</style></head>
<body style="background:#f9fafb;padding:20px">
<div class="wrap"><div class="box">
  <h2>{{ $approved ? 'อนุมัติแล้ว' : 'ไม่อนุมัติ' }} <span class="muted">/ EN & MY included below</span></h2>
  <p class="muted">Ref: #{{ $id }}</p>

  <table>
    <tr><td class="label">คอร์ส / Course</td><td>{{ $course }}</td></tr>
    <tr><td class="label">วันที่เรียน / Date</td><td>{{ $date }}</td></tr>
    <tr><td class="label">จำนวนผู้เรียน / Quantity</td><td>{{ $qty }}</td></tr>
    <tr><td class="label">ยอดรวม / Total</td><td>{{ $sum }} THB</td></tr>
  </table>

  <div style="margin:16px 0 8px">
    <a href="{{ $detailUrl }}" class="btn">ดูสถานะ / View status / Lihat status</a>
  </div>

  <div class="hr"></div>
  <div class="tag">ภาษาไทย</div>
  <p>{{ $approved ? 'คำขอจองคอร์สของคุณได้รับการอนุมัติแล้ว ✅' : 'ขออภัย คำขอจองคอร์สของคุณไม่ได้รับการอนุมัติ ❌' }}</p>

  <div class="hr"></div>
  <div class="tag">English</div>
  <p>{{ $approved ? 'Your booking has been approved.' : 'Sorry, your booking was not approved.' }}</p>

  <div class="hr"></div>
  <div class="tag">Bahasa Melayu</div>
  <p>{{ $approved ? 'Tempahan anda telah diluluskan.' : 'Maaf, tempahan anda tidak diluluskan.' }}</p>

  <div class="hr"></div>
  <p class="muted">Siro-Secret • อีเมลอัตโนมัติ กรุณาอย่าตอบกลับ</p>
</div></div>
</body></html>
