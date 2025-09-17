<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

@php
    use Illuminate\Support\Facades\Auth;
    $isAuth = Auth::check();

    // ลิงก์แบรนด์ (กลับหน้าแรก)
    $brandHref = $isAuth ? route('member.dashboard') : route('guest.products');

    // ลิงก์เมนูหลัก (เปลี่ยนตามสถานะ)
    $routeProducts = $isAuth ? route('member.product') : route('guest.products');
    $routeCourses = $isAuth ? route('member.courses') : route('guest.courses');
    $routeAbout = $isAuth ? route('member.aboutme') : route('aboutme');
    $routeContact = $isAuth ? route('member.contect') : route('contect');

    // เช็ก active ได้ทั้ง guest/member
    $activeProducts = request()->routeIs('member.product*') || request()->routeIs('guest.products*') ? 'active' : '';
    $activeCourses =
        request()->routeIs('member.courses') ||
        request()->routeIs('member.course.*') ||
        request()->routeIs('guest.courses*')
            ? 'active'
            : '';
    $activeAbout = request()->routeIs('member.aboutme') || request()->routeIs('aboutme') ? 'active' : '';
    $activeContact = request()->routeIs('member.contect') || request()->routeIs('contect') ? 'active' : '';
@endphp

<style>
    /* ความสูงของแถบเมนู */
    .nav-bar {
        height: 52px;
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        padding: 0 20px;
    }

    .nav-center,
    .nav-right {
        display: flex;
        gap: 30px;
    }

    .nav-bar a {
        display: flex;
        align-items: center;
        height: 100%;
        padding: 0 16px;
        text-decoration: none;
        border-radius: 0;
    }

    .nav-bar a.active {
        background: #d9d9d9;
        color: #000;
    }

    .nav-bar a:hover {
        background: #d9d9d9;
    }

    .language-switcher a.active {
        background-color: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
    }

    .language-switcher a {
        text-decoration: none;
        color: #000;
        font-weight: bold;
        margin: 0 2px;
        padding: 5px 10px;
        border: 1px solid #babbba;
        border-radius: 5px;
        transition: .3s;
    }

    .language-switcher a:hover {
        background-color: #2e7d32;
        color: #fff;
    }

    .cart-icon {
        display: inline-block;
        position: relative;
        margin-left: 10px;
        text-decoration: none;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc3545;
        color: #fff;
        font-size: .75rem;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 50%;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 2px rgba(0, 0, 0, .3);
    }

    .brand-link,
    .brand-link:link,
    .brand-link:visited,
    .brand-link:hover,
    .brand-link:active {
        text-decoration: none;
        color: inherit;
    }

    .brand-link .brand-title,
    .brand-link .brand-subtitle {
        text-decoration: none;
    }
</style>

@if (session('error'))
    <div class="alert alert-warning" role="alert" style="margin-top:10px;">
        {{ session('error') }}
    </div>
@endif

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="brand">
            <a href="{{ $brandHref }}" class="brand-link" aria-label="กลับหน้าแรก">
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

                <i class="fas fa-language icon"></i>
                @php $currentLocale = app()->getLocale(); @endphp
                <div class="language-switcher" style="display:inline-block; margin-left:10px;">
                    <a href="{{ route('lang.switch', 'th') }}"
                        class="{{ $currentLocale === 'th' ? 'active' : '' }}">TH</a>|
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="{{ $currentLocale === 'en' ? 'active' : '' }}">EN</a>|
                    <a href="{{ route('lang.switch', 'ms') }}"
                        class="{{ $currentLocale === 'ms' ? 'active' : '' }}">MY</a>
                </div>
            </div>

            <div class="icon-right">
                @if ($isAuth)
                    <a href="{{ route('member.cart') }}" class="cart-icon">
                        <i class="fa-solid fa-cart-shopping" style="font-size:1.3rem;color:#333;"></i>
                        @if (($cartCount ?? 0) > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('member.course.booking.list') }}" class="fas fa-calendar icon"></a>
                    <a href="{{ route('profile.edit') }}" class="fas fa-user icon"></a>
                @else
                    {{-- ผู้ใช้ทั่วไป: แสดงปุ่ม login แทนไอคอน member --}}
                    <a href="{{ route('login') }}" class="fas fa-user icon" title="Login"></a>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            <a href="{{ $routeProducts }}" class="{{ $activeProducts }}"
                aria-current="{{ $activeProducts ? 'page' : '' }}">
                {{ __('messages.product') }}
            </a>

            <a href="{{ $routeCourses }}" class="{{ $activeCourses }}"
                aria-current="{{ $activeCourses ? 'page' : '' }}">
                {{ __('messages.course') }}
            </a>

            <a href="{{ $routeAbout }}" class="{{ $activeAbout }}"
                aria-current="{{ $activeAbout ? 'page' : '' }}">
                {{ __('messages.about_me') }}
            </a>
        </div>

        <div class="nav-right">
            <a href="{{ $routeContact }}" class="{{ $activeContact }}"
                aria-current="{{ $activeContact ? 'page' : '' }}">
                {{ __('messages.contact') }}
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container mt-5">
        @yield('content')
    </div>
    @include('partials.flash-popup')
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
