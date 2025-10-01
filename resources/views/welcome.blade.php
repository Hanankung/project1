<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('CSS/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

@php
    $currentLocale = app()->getLocale();
@endphp

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="brand">
            <a href="/" class="brand-link" aria-label="ไปหน้าแรก">
                <div class="brand-title">Siro-Secret</div>
                <div class="brand-subtitle">ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print</div>
            </a>
        </div>

        <div class="top-right">
            <div class="search-center">
                <form action="{{ route('search') }}" method="GET" style="display:flex; align-items:center; gap:10px;">
                    <input type="text" name="query" class="search-input" placeholder="{{ __('messages.search') }}">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <i class="fas fa-language icon" aria-hidden="true"></i>
                <div class="language-switcher" style="display:inline-block; margin-left:10px;">
                    <a class="{{ $currentLocale === 'th' ? 'active' : '' }}"
                        href="{{ route('lang.switch', 'th') }}">TH</a> |
                    <a class="{{ $currentLocale === 'en' ? 'active' : '' }}"
                        href="{{ route('lang.switch', 'en') }}">EN</a> |
                    <a class="{{ $currentLocale === 'ms' ? 'active' : '' }}"
                        href="{{ route('lang.switch', 'ms') }}">MY</a>
                </div>
            </div>

            <div class="icon-right">
                @auth
                    <a class="cart-icon" href="{{ route('member.cart') }}" aria-label="ตะกร้าสินค้า">
                    @else
                        <a class="cart-icon" href="#" aria-label="ตะกร้าสินค้า" data-auth="required"
                            data-auth-title="{{ __('messages.auth_required_title_default') }}"
                            data-auth-message="{{ __('messages.auth_required_msg_default') }}">
                        @endauth
                        <i class="fas fa-shopping-cart icon"></i>
                        @isset($cartCount)
                            @if ($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        @endisset
                    </a>
                    <i class="fas fa-calendar icon" aria-hidden="true"></i>
                    <a href="{{ route('login') }}" class="fas fa-user icon" aria-label="เข้าสู่ระบบ"></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            {{-- สินค้า: ให้ active ทั้งหน้ารายการและหน้ารายละเอียด --}}
            <a class="{{ request()->routeIs('guest.products*') ? 'active' : '' }}"
                href="{{ route('guest.products') }}">
                {{ __('messages.product') }}
            </a>

            {{-- คอร์สเรียน: ให้ active ทั้งหน้ารายการและหน้ารายละเอียด --}}
            <a class="{{ request()->routeIs('guest.courses*') ? 'active' : '' }}" href="{{ route('guest.courses') }}">
                {{ __('messages.course') }}
            </a>

            {{-- เกี่ยวกับเรา --}}
            <a class="{{ request()->routeIs('aboutme') ? 'active' : '' }}" href="{{ route('aboutme') }}">
                {{ __('messages.about_me') }}
            </a>
        </div>

        <div class="nav-right">
            {{-- ติดต่อเรา (route ของคุณสะกด contect) --}}
            <a class="{{ request()->routeIs('contect') ? 'active' : '' }}" href="{{ route('contect') }}">
                {{ __('messages.contact') }}
            </a>
        </div>
    </div>

    <!-- Banner -->
    <div class="ecoimg">
        <img src="{{ asset('image/snim.jpg') }}" alt="Eco Print Banner" class="eco-banner">
    </div>

    <!-- Hero -->
    <div class="hero">
        {{ __('messages.subtitle 1') }}<br>
        {{ __('messages.subtitle') }}
    </div>

    <!-- 3 Cards -->
    <div class="three-images">
        <div class="image-card">
            <img src="{{ asset('image/ex_product1.jpg') }}" alt="ภาพที่ 1">
            <p>{{ __('messages.title') }}</p>
        </div>
        <div class="image-card">
            <img src="{{ asset('image/ex_product2.jpg') }}" alt="ภาพที่ 2">
            <p>{{ __('messages.title 1') }}</p>
        </div>
        <div class="image-card">
            <img src="{{ asset('image/ex_product3.jpg') }}" alt="ภาพที่ 3">
            <p>{{ __('messages.title 2') }}</p>
        </div>
    </div>
</body>

</html>
