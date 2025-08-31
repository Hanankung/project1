<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="brand">
            <div class="brand-title">Siro-Secret</div>
            <div class="brand-subtitle">ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print</div>
        </div>
        <div class="top-right">
            <div class="search-center">
                <input type="text" class="search-input" placeholder="{{ __('messages.search') }}">
                <button class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <i class="fas fa-language icon"></i>
                <div class="language-switcher" style="display:inline-block; margin-left:10px;">
                    <a href="{{ route('lang.switch', 'th') }}">ไทย</a> |
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
    <div class="container mt-5">
        @yield('content')
    </div>
</body>
