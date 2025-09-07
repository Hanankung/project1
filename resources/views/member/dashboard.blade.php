<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
   /* ความสูงของแถบเมนู */
    .nav-bar {
        height: 52px;
        /* ปรับได้ */
        display: flex;
        align-items: stretch;
        /* ให้ลูกยืดเต็มความสูง */
        justify-content: space-between;
        padding: 0 20px;
    }

    /* กล่องลิงก์ให้เรียงเป็นแถว */
    .nav-center,
    .nav-right {
        display: flex;
        gap: 30px;
    }

    /* ลิงก์เมนูให้สูงเท่าบาร์ และจัดตัวหนังสือกึ่งกลางแนวตั้ง */
    .nav-bar a {
        display: flex;
        align-items: center;
        height: 100%;
        /* สำคัญ: สูงเท่าบาร์ */
        padding: 0 16px;
        /* เว้นซ้าย-ขวา */
        text-decoration: none;
        border-radius: 0;
        /* ไม่ต้องโค้ง */
    }

    /* สีตอน active ให้เป็นเทาเต็มความสูง */
    .nav-bar a.active {
        background: #d9d9d9;
        /* เทา */
        color: #000;
    }

    /* hover (ถ้าต้องการ) */
    .nav-bar a:hover {
        background:#d9d9d9;
    }

    /* ปุ่มภาษา */
    .language-switcher a.active {
        background-color: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
    }

    .language-switcher a {
        text-decoration: none;
        color: #000000;
        font-weight: bold;
        margin: 0 2px;
        padding: 5px 10px;
        border: 1px solid #babbba;
        border-radius: 5px;
        transition: 0.3s;
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

    /* Badge แดงแสดงจำนวนสินค้า */
    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background-color: #dc3545;
        /* แดง */
        color: #fff;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 50%;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.3);
    }

    /* ทำให้ลิงก์โลโก้ไม่ขึ้นขีดทุกสถานะ */
    .brand-link,
    .brand-link:link,
    .brand-link:visited,
    .brand-link:hover,
    .brand-link:active {
        text-decoration: none;
        color: inherit;
    }

    /* กันกรณีมีสไตล์อื่นไปใส่ underline ในลูก */
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
            <a href="{{ route('member.dashboard') }}" class="brand-link" aria-label="ไปหน้าแดชบอร์ด">
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
                        class="{{ $currentLocale === 'ms' ? 'active' : '' }}">MS</a>
                </div>
            </div>

            <div class="icon-right">
                <a href="{{ route('member.cart') }}" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 1.3rem; color: #333;"></i>
                    @if (($cartCount ?? 0) > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <a href="/member/courseBookingList" class="fas fa-calendar icon"></a>
                <a href="/profile" class="fas fa-user icon"></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            <a href="{{ route('member.product') }}"
                class="{{ request()->routeIs('member.product*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('member.product*') ? 'page' : '' }}">
                {{ __('messages.product') }}
            </a>

            <a href="{{ route('member.courses') }}"
                class="{{ request()->routeIs('member.courses', 'member.course.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('member.courses') || request()->routeIs('member.course.*') ? 'page' : '' }}">
                {{ __('messages.course') }}
            </a>

            <a href="{{ route('member.aboutme') }}" class="{{ request()->routeIs('member.aboutme') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('member.aboutme') ? 'page' : '' }}">
                {{ __('messages.about_me') }}
            </a>
        </div>

        <div class="nav-right">
            <a href="{{ route('member.contect') }}" class="{{ request()->routeIs('member.contect') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('member.contect') ? 'page' : '' }}">
                {{ __('messages.contact') }}
            </a>
        </div>
    </div>

    <div class="ecoimg">
        <img src="{{ asset('image/snim.jpg') }}" alt="Eco Print Banner" class="eco-banner">
    </div>
    <div class="hero">
        {{ __('messages.subtitle 1') }}<br>
        {{ __('messages.subtitle') }}
    </div>

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
