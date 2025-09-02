<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
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
</style>
@if(session('error'))
    <div class="alert alert-warning" role="alert" style="margin-top:10px;">
        {{ session('error') }}
    </div>
@endif
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="brand">
            <div class="brand-title">Siro-Secret</div>
            <div class="brand-subtitle">ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print</div>
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
                <div class="language-switcher" style="display:inline-block; margin-left:10px;">
                    <a href="{{ route('lang.switch', 'th') }}">TH</a> |
                    <a href="{{ route('lang.switch', 'en') }}">EN</a> |
                    <a href="{{ route('lang.switch', 'ms') }}">MS</a>
                </div>
            </div>

            <div class="icon-right">
                <a href="{{ route('member.cart') }}" class="cart-icon position-relative">
                    <i class="bi bi-cart3" style="font-size: 1.3rem; color: #333;"></i>
                    @if ($cartCount > 0)
                        <span class="cart-badge">
                            {{ $cartCount }}
                        </span>
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
            <a href="/member/product">{{ __('messages.product') }}</a>
            <a href="/member/courses">{{ __('messages.course') }}</a>
            <a href="/member/aboutme">{{ __('messages.about_me') }}</a>
        </div>
        <div class="nav-right">
            <a href="/member/contect">{{ __('messages.contact') }}</a>
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
