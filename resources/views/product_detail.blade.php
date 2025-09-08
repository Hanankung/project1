@extends('layout')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .product-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* ปุ่มย้อนกลับ */
        .back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 24px;
            color: #333;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #0d6efd;
        }

        .product-image {
            max-width: 300px;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .product-details p {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .btn-group-custom {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cart {
            background-color: #ffc107;
            color: #fff;
            flex: 1;
        }

        .btn-cart:hover {
            background-color: #e0a800;
            color: #fff;
        }

        .btn-buy {
            background-color: #28a745;
            color: #fff;
            flex: 1;
        }

        .btn-buy:hover {
            background-color: #218838;
            color: #fff;
        }
    </style>
</head>
@php
        $locale = App::getLocale();
        $nameField = $locale === 'en' ? 'product_name_ENG' : ($locale === 'ms' ? 'product_name_MS' : 'product_name');
        $descField = $locale === 'en' ? 'description_ENG' : ($locale === 'ms' ? 'description_MS' : 'description');
        $materialField = $locale === 'en' ? 'material_ENG' : ($locale === 'ms' ? 'material_MS' : 'material');
    @endphp
<div class="container mt-5">
    <div class="product-container">
        <!-- ปุ่มย้อนกลับ -->
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="bi bi-arrow-left-circle"></i>
        </a>

        <div class="row align-items-center">
            <!-- รูปสินค้า (ซ้าย) -->
            <div class="col-md-5 text-center">
                @if ($product->product_image)
                    <img src="{{ asset($product->product_image) }}" class="product-image"
                        alt="{{ $product->product_name }}">
                @else
                    <img src="{{ asset('images/default.png') }}" class="product-image" alt="ไม่มีรูปภาพ">
                @endif
            </div>

            <!-- ข้อมูลสินค้า (ขวา) -->
            <div class="col-md-7 product-details">
                <h1 class="product-title">{{ $product->$nameField ?? '-' }}</h1>
                    <p><strong>{{ __('messages.price') }}:</strong> {{ number_format($product->price, 2) }}
                        {{ __('messages.baht') }}</p>
                    {{-- <p><strong>{{ __('messages.description') }}:</strong> {{ $product->$descField ?? '-' }}</p> --}}
                    <p><strong>{{ __('messages.quantity') }}:</strong> {{ $product->quantity ?? '-' }}</p>
                    <p><strong>{{ __('messages.material') }}:</strong> {{ $product->$materialField ?? '-' }}</p>
                    <p><strong>{{ __('messages.size') }}:</strong> {{ $product->size ?? '-' }}</p>

                     {{-- ===== แยกหัวข้อ รายละเอียด/คุณสมบัติ/การดูแลรักษา (รองรับ TH/EN/MS) ===== --}}
        @php
          $raw = (string)($product->$descField ?? '');
          $labelMap = [
            'th' => ['desc'=>['รายละเอียด'], 'features'=>['คุณสมบัติ'], 'care'=>['การดูแลรักษา']],
            'en' => ['desc'=>['Details','Description'], 'features'=>['Features'], 'care'=>['Care','Care Instructions','How to care']],
            'ms' => ['desc'=>['Perincian','Keterangan'], 'features'=>['Ciri-ciri','Ciri'], 'care'=>['Penjagaan','Cara penjagaan']],
          ];
          $display = [
            'th' => ['desc'=>'รายละเอียด', 'features'=>'คุณสมบัติ', 'care'=>'การดูแลรักษา'],
            'en' => ['desc'=>'Details', 'features'=>'Features', 'care'=>'Care'],
            'ms' => ['desc'=>'Perincian', 'features'=>'Ciri-ciri', 'care'=>'Penjagaan'],
          ];
          $labels = $labelMap[$locale] ?? $labelMap['en'];
          $titles = $display[$locale]  ?? $display['en'];

          $allNames = [];
          foreach ($labels as $arr) { $allNames = array_merge($allNames, $arr); }
          $pregQuote = fn($v) => preg_quote($v, '/');
          $joinAlt   = fn($arr) => implode('|', array_map($pregQuote, $arr));
          $allAlt    = $joinAlt($allNames);

          $extract = function(string $text, array $names) use ($allAlt, $joinAlt) {
            $thisAlt = $joinAlt($names);
            $pattern = "/(?:{$thisAlt})\\s*:\\s*(.*?)(?=(?:{$allAlt})\\s*:|$)/isu";
            return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
          };

          $sec = [
            'desc'     => $extract($raw, $labels['desc']),
            'features' => $extract($raw, $labels['features']),
            'care'     => $extract($raw, $labels['care']),
          ];

          $splitLines = function(?string $s) {
            if (!$s) return [];
            $parts = preg_split('/\r\n|\r|\n|[•·]|[–—-]/u', $s);
            return array_values(array_filter(array_map('trim', $parts), fn($t)=>$t!==''));
          };
          $features = $splitLines($sec['features']);
          $care     = $splitLines($sec['care']);

          $noSections = empty($sec['desc']) && empty($features) && empty($care);
        @endphp

        @if($noSections && $raw !== '')
          <h5 class="mt-3">{{ $titles['desc'] }}</h5>
          <p style="white-space:pre-line">{{ $raw }}</p>
        @else
          @if(!empty($sec['desc']))
            <h5 class="mt-3">{{ $titles['desc'] }}</h5>
            <p style="white-space:pre-line">{{ $sec['desc'] }}</p>
          @endif

          @if(!empty($features))
            <h5 class="mt-3">{{ $titles['features'] }}</h5>
            <ul class="mb-3">
              @foreach($features as $f) <li>{{ $f }}</li> @endforeach
            </ul>
          @endif

          @if(!empty($care))
            <h5 class="mt-3">{{ $titles['care'] }}</h5>
            <ul class="mb-3">
              @foreach($care as $c) <li>{{ $c }}</li> @endforeach
            </ul>
          @endif
        @endif
        {{-- ===== จบส่วนแยกหัวข้อ ===== --}}
                <!-- ปุ่มตะกร้า + สั่งซื้อ -->
                <div class="btn-group-custom">
                    <form action="{{ route('cart.store') }}" method="POST" style="margin:0; padding:0;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-cart">
                                    <i class="bi bi-cart-plus"> {{ __('messages.add_to_cart') }}</i>
                                </button>
                            </form>
                    <a href="/login" class="btn btn-buy">
                        <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
