{{-- resources/views/search/results.blade.php --}}
@extends('member.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
  <h3 class="mb-3">
    🔎 {{ __('messages.search_results') ?? 'ผลการค้นหา' }}:
    <span class="text-primary">"{{ $query }}"</span>
  </h3>

  {{-- แท็บเลือกดู "สินค้า" / "คอร์ส" --}}
  <ul class="nav nav-pills mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab-products" data-bs-toggle="pill" data-bs-target="#pane-products"
              type="button" role="tab">
        {{ __('messages.products') ?? 'สินค้า' }}
        <span class="badge text-bg-secondary">{{ $products->total() }}</span>
      </button>
    </li>
    <li class="nav-item ms-2" role="presentation">
      <button class="nav-link" id="tab-courses" data-bs-toggle="pill" data-bs-target="#pane-courses"
              type="button" role="tab">
        {{ __('messages.courses') ?? 'คอร์ส' }}
        <span class="badge text-bg-secondary">{{ $courses->total() }}</span>
      </button>
    </li>
  </ul>

  <div class="tab-content">

    {{-- ======= ผลลัพธ์สินค้า ======= --}}
    <div class="tab-pane fade show active" id="pane-products" role="tabpanel" aria-labelledby="tab-products">
      @if ($products->count())
        <div class="row g-3">
          @foreach ($products as $p)
            @php
              // ใช้ชื่อสินค้าตามภาษา (fallback เป็น product_name ถ้าไม่มี)
              $pTitle = $p->name_i18n ?? $p->product_name;  // 👈 เปลี่ยน
              $img = $p->product_image ?? null;
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
              <div class="card h-100 shadow-sm">
                <img src="{{ $img ? asset($img) : 'https://via.placeholder.com/600x600?text=Product' }}"
                     class="card-img-top" alt="{{ $pTitle }}"   {{-- 👈 เปลี่ยน --}}
                     style="object-fit:cover; height:220px;">

                <div class="card-body d-flex flex-column">
                  <h6 class="card-title fw-semibold" style="min-height:3em;">
                    {{ $pTitle }}  {{-- 👈 เปลี่ยน --}}
                  </h6>

                  @if(isset($p->price))
                    <div class="mb-2 text-muted small">{{ __('messages.price') ?? 'ราคา' }}:
                      <span class="fw-semibold">{{ number_format($p->price, 2) }}</span>
                      {{ __('messages.baht') ?? 'บาท' }}
                    </div>
                  @endif

                  <div class="mt-auto d-grid gap-2">
                    @if($isAuth)
                      <a class="btn btn-primary" href="{{ route('member.product.show', $p->id) }}">
                        {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                      </a>
                    @else
                      <a class="btn btn-primary" href="{{ route('guest.products.show', $p->id) }}">
                        {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-3">
          {{ $products->appends(['courses_page' => request('courses_page')])->links() }}
        </div>
      @else
        <div class="alert alert-info">{{ __('messages.no_products') ?? 'ไม่พบสินค้า' }}</div>
      @endif
    </div>

    {{-- ======= ผลลัพธ์คอร์ส ======= --}}
    <div class="tab-pane fade" id="pane-courses" role="tabpanel" aria-labelledby="tab-courses">
      @if ($courses->count())
        <div class="row g-3">
          @foreach ($courses as $c)
            @php
              // ใช้ชื่อคอร์สตามภาษา (model Course ของคุณมี accessor name_i18n อยู่แล้ว)
              $cTitle = $c->name_i18n ?? $c->course_name;  // 👈 เปลี่ยน
              $cimg = $c->course_image ?? null;
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
              <div class="card h-100 shadow-sm">
                <img src="{{ $cimg ? asset($cimg) : 'https://via.placeholder.com/800x450?text=Course' }}"
                     class="card-img-top" alt="{{ $cTitle }}"   {{-- 👈 เปลี่ยน --}}
                     style="object-fit:cover; height:200px;">
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title fw-semibold">{{ $cTitle }}</h6> {{-- 👈 เปลี่ยน --}}
                  @if(isset($c->price))
                    <div class="mb-2 text-muted small">{{ __('messages.price') ?? 'ราคา' }}:
                      <span class="fw-semibold">{{ number_format($c->price, 2) }}</span>
                      {{ __('messages.baht') ?? 'บาท' }}
                    </div>
                  @endif
                  <div class="mt-auto d-grid gap-2">
                    @if($isAuth)
                      <a class="btn btn-success" href="{{ route('member.course.detail', $c->id) }}">
                        {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                      </a>
                    @else
                      <a class="btn btn-success" href="{{ route('guest.courses.show', $c->id) }}">
                        {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-3">
          {{ $courses->appends(['products_page' => request('products_page')])->links() }}
        </div>
      @else
        <div class="alert alert-info">{{ __('messages.no_courses') ?? 'ไม่พบคอร์ส' }}</div>
      @endif
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
