{{-- resources/views/search/results.blade.php --}}
@extends('member.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  :root{
    --brand:#2e7d32;       /* เขียวหลัก */
    --brand-d:#1f5f24;     /* เขียวเข้ม */
    --ink:#1f2937; --muted:#64748b;
  }

  /* หัวผลการค้นหา */
  .search-hero{ display:flex; align-items:center; gap:10px; margin:14px 0 18px; }
  .search-hero .q{ color:#0ea5a3; text-decoration:none; border-bottom:2px solid #a5f3fc; }

  /* ปรับ Nav Pills ให้เป็นโทนแบรนด์ */
  .nav-pills .nav-link{
    border-radius:999px; font-weight:700;
    border:1px solid #e5e7eb; color:#334155; background:#fff;
  }
  .nav-pills .nav-link .badge{ background:#eef2ff; color:#1f2937; }
  .nav-pills .nav-link.active{
    color:var(--brand); background:linear-gradient(180deg,#fff,#f6fff6);
    border-color:rgba(46,125,50,.25);
  }

  /* การ์ดผลลัพธ์ */
  .result-card{
    border:1px solid #eef0f2; border-radius:16px; overflow:hidden; background:#fff;
    box-shadow:0 14px 34px rgba(0,0,0,.06); transition:.25s ease;
  }
  .result-card:hover{ transform:translateY(-5px); box-shadow:0 20px 44px rgba(0,0,0,.10); }
  .result-thumb{ width:100%; height:220px; object-fit:cover; display:block; }
  .result-title{ font-weight:800; color:var(--ink); line-height:1.25; min-height:3em; }
  .result-price{
    background:linear-gradient(180deg,#fff,#f7fff7);
    border:1px solid rgba(46,125,50,.12);
    border-radius:10px; padding:6px 10px; color:#334155; font-weight:600;
  }

  /* ปุ่มโทนแบรนด์ */
  .btn-brand{
    background:linear-gradient(180deg,var(--brand),#32903a);
    color:#fff; border:none; border-radius:12px; font-weight:700; letter-spacing:.2px;
    box-shadow:0 12px 26px rgba(46,125,50,.22);
  }
  .btn-brand:hover{ filter:brightness(.98); color:#fff; }
  .btn-outline-brand{
    border:1px solid rgba(46,125,50,.25); color:var(--brand); background:#fff; border-radius:12px; font-weight:700;
  }
  .btn-outline-brand:hover{ background:var(--brand); color:#fff; }

  @media (max-width:576px){ .result-thumb{ height:200px; } }
</style>

<div class="container py-4">
  <div class="search-hero">
    <h3 class="m-0 fw-bold">
      🔎 {{ __('messages.search_results') ?? 'ผลการค้นหา' }}:
      <span class="q">"{{ $query }}"</span>
    </h3>
  </div>

  {{-- แท็บเลือกดู "สินค้า" / "คอร์ส" --}}
  <ul class="nav nav-pills mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab-products" data-bs-toggle="pill" data-bs-target="#pane-products"
              type="button" role="tab">
        {{ __('messages.products') ?? 'สินค้า' }}
        <span class="badge">{{ $products->total() }}</span>
      </button>
    </li>
    <li class="nav-item ms-2" role="presentation">
      <button class="nav-link" id="tab-courses" data-bs-toggle="pill" data-bs-target="#pane-courses"
              type="button" role="tab">
        {{ __('messages.courses') ?? 'คอร์ส' }}
        <span class="badge">{{ $courses->total() }}</span>
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
              $pTitle = $p->name_i18n ?? $p->product_name;
              $img = $p->product_image ?? null;
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
              <div class="result-card h-100">
                <a href="{{ $isAuth ? route('member.product.show',$p->id) : route('guest.products.show',$p->id) }}">
                  <img src="{{ $img ? asset($img) : 'https://via.placeholder.com/600x600?text=Product' }}"
                       class="result-thumb" alt="{{ $pTitle }}">
                </a>

                <div class="p-3 d-flex flex-column">
                  <div class="result-title">{{ $pTitle }}</div>

                  @if(isset($p->price))
                    <div class="result-price mb-2">
                      {{ __('messages.price') ?? 'ราคา' }}: {{ number_format($p->price, 2) }} {{ __('messages.baht') ?? 'บาท' }}
                    </div>
                  @endif

                  <div class="mt-auto d-grid gap-2">
                    <a class="btn btn-outline-brand"
                       href="{{ $isAuth ? route('member.product.show',$p->id) : route('guest.products.show',$p->id) }}">
                      {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                    </a>
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
              $cTitle = $c->name_i18n ?? $c->course_name;
              $cimg = $c->course_image ?? null;
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
              <div class="result-card h-100">
                <a href="{{ $isAuth ? route('member.course.detail',$c->id) : route('guest.courses.show',$c->id) }}">
                  <img src="{{ $cimg ? asset($cimg) : 'https://via.placeholder.com/800x450?text=Course' }}"
                       class="result-thumb" alt="{{ $cTitle }}">
                </a>
                <div class="p-3 d-flex flex-column">
                  <div class="result-title" style="min-height:auto">{{ $cTitle }}</div>

                  @if(isset($c->price))
                    <div class="result-price mb-2">
                      {{ __('messages.price') ?? 'ราคา' }}: {{ number_format($c->price, 2) }} {{ __('messages.baht') ?? 'บาท' }}
                    </div>
                  @endif

                  <div class="mt-auto d-grid gap-2">
                    <a class="btn btn-brand"
                       href="{{ $isAuth ? route('member.course.detail',$c->id) : route('guest.courses.show',$c->id) }}">
                      {{ __('messages.view_detail') ?? 'รายละเอียด' }}
                    </a>
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
