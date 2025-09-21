@extends('layout')

@section('content')
  {{-- ไอคอน + สไตล์ของหน้าติดต่อ --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('CSS/contect.css') }}">

  @php
    // ✅ ลิงก์โลเคชั่นที่ให้มา
    $mapsUrl = 'https://www.google.com/maps/place/7%C2%B018\'12.3%22N+100%C2%B010\'53.3%22E/@7.303414,100.1789031,17z/data=!3m1!4b1!4m4!3m3!8m2!3d7.303414!4d100.181478?entry=ttu&g_ep=EgoyMDI1MDkxMC4wIKXMDSoASAFQAw%3D%3D';

    // ที่อยู่ (ใช้เป็น fallback/แสดงผล)
    $addr = '13 หมู่ที่ 2 ตำบลป่าบอน อำเภอป่าบอน จังหวัดพัทลุง';

    // ✅ พิกัดจากลิงก์: 7.303414, 100.181478
    $lat = 7.303414;
    $lng = 100.181478;

    // ✅ ให้ฝังแผนที่ด้วยพิกัดเสมอ
    $useLatLng = true;
  @endphp

  <div class="container my-4">

    <h3>{{ __('messages.contact') }}🌿</h3>

    <div class="contact-info">
      <p>{{ __('messages.details 1') }}</p>
      <p>by Siro-Secret</p>
      <p>📞 097-3502899</p>
      <p>📍{{ __('messages.details 2') }}</p>
    </div>

    <div class="social-box mt-3">
      <a href="#" class="facebook"><i class="fab fa-facebook"></i> Eco print by Siro</a>
      <a href="mailto:secretsiro14@gmail.com" class="email"><i class="fas fa-envelope"></i> secretsiro14@gmail.com</a>
      <a href="#" class="line"><i class="fab fa-line"></i> @sirosecret</a>
    </div>

    <div class="support-section mt-4">
      <strong>{{ __('messages.Supported by') }} :</strong>
      <div class="support-logos">
        <img src="{{ asset('image/logo1.png') }}" alt="Logo1">
        <img src="{{ asset('image/logo2.png') }}" alt="Logo2">
        <img src="{{ asset('image/logo3.png') }}" alt="Logo3">
        <img src="{{ asset('image/logo4.png') }}" alt="Logo4">
        <img src="{{ asset('image/logo5.png') }}" alt="Logo5">
        <img src="{{ asset('image/logo6.png') }}" alt="Logo6">
        <img src="{{ asset('image/logo7.png') }}" alt="Logo7">
        <img src="{{ asset('image/logo8.png') }}" alt="Logo8">
        <img src="{{ asset('image/logo9.png') }}" alt="Logo9">
      </div>
    </div>

    {{-- แผนที่ร้าน (Google Maps iframe) --}}
    <div class="card mt-4" style="border:0; box-shadow:0 8px 18px rgba(0,0,0,.06); border-radius:16px;">
      <div class="card-body">
        <h5 class="mb-2">
          <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ __('messages.shop_map') }}
        </h5>

        <div style="border-radius:12px; overflow:hidden;">
          <iframe
            src="{{ $useLatLng
                    ? "https://www.google.com/maps?q={$lat},{$lng}&z=16&output=embed"
                    : 'https://www.google.com/maps?q=' . urlencode($addr) . '&z=16&output=embed' }}"
            width="100%" height="380" style="border:0"
            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

        <div class="mt-2 d-flex gap-2 flex-wrap">
          {{-- ปุ่มเปิดหน้าสถานที่ใน Google Maps --}}
          <a class="btn btn-outline-primary btn-sm" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
            <i class="fa-brands fa-google me-1"></i>{{ __('messages.open_in_google_maps') }}
          </a>

          {{-- ปุ่มนำทางไปยังพิกัด (โหมดขับรถ) --}}
          <a class="btn btn-primary btn-sm"
             href="https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $lng }}&travelmode=driving"
             target="_blank" rel="noopener">
            <i class="fa-solid fa-route me-1"></i>{{ __('messages.navigate') ?? 'นำทาง' }}
          </a>
        </div>
      </div>
    </div>

  </div>
@endsection
