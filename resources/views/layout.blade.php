<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>

    {{-- สไตล์เดิมของคุณ --}}
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap CSS (สำหรับ Modal) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Elegant auth modal */
        .modal-auth .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        }

        .modal-auth .modal-header {
            padding: 14px 16px;
            border: 0;
            background: linear-gradient(180deg, #ffffff 40%, #f7faf9 100%);
        }

        .modal-auth .modal-title {
            font-weight: 800;
            letter-spacing: .2px;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-auth .header-icon {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: rgba(34, 197, 94, .12);
            color: #059669;
            /* เขียว */
        }

        .modal-auth .modal-body {
            font-size: .98rem;
            color: #334155;
            line-height: 1.6;
        }

        .modal-auth .modal-footer {
            border: 0;
            padding-top: 0;
            gap: 8px;
        }

        .btn-auth-login {
            border-color: #94a3b8;
        }

        .btn-auth-login:hover {
            border-color: #64748b;
        }

        .btn-auth-register {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border: 0;
            font-weight: 700;
        }

        .btn-auth-register:hover {
            filter: brightness(.98);
        }

        .btn-auth-cancel {
            color: #475569;
        }
    </style>

</head>

@php
    $currentLocale = app()->getLocale();
@endphp

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="brand">
            <a href="/" class="brand-link" aria-label="ไปหน้าแดชบอร์ด">
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
                <a class="cart-icon" href="{{ route('member.cart') }}" aria-label="ตะกร้าสินค้า">
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
            {{-- สินค้า --}}
            <a class="{{ request()->routeIs('guest.products*') ? 'active' : '' }}"
                href="{{ route('guest.products') }}">
                {{ __('messages.product') }}
            </a>

            {{-- คอร์สเรียน --}}
            <a class="{{ request()->routeIs('guest.courses*') ? 'active' : '' }}" href="{{ route('guest.courses') }}">
                {{ __('messages.course') }}
            </a>

            {{-- เกี่ยวกับเรา --}}
            <a class="{{ request()->routeIs('aboutme') ? 'active' : '' }}" href="{{ route('aboutme') }}">
                {{ __('messages.about_me') }}
            </a>
        </div>

        <div class="nav-right">
            {{-- ติดต่อเรา (สะกด contect ตาม route เดิมของคุณ) --}}
            <a class="{{ request()->routeIs('contect') ? 'active' : '' }}" href="{{ route('contect') }}">
                {{ __('messages.contact') }}
            </a>
        </div>
    </div>

    <div class="container mt-5">
        @yield('content')
    </div>

    {{-- ===== Modal: ต้องเป็นสมาชิกก่อน ===== --}}
    <div class="modal fade modal-auth" id="authRequiredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="authRequiredTitle">
                        <span class="header-icon"><i class="fa-solid fa-lock"></i></span>
                        {{ __('messages.auth_required_title_default') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>

                <div class="modal-body">
                    <div id="authRequiredText">
                        {{ __('messages.auth_required_msg_default') }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-auth-cancel" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-auth-login">{{ __('messages.auth.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-success btn-auth-register">{{ __('messages.register') }}</a>
                </div>

            </div>
        </div>
    </div>

    {{-- Bootstrap Bundle (มี Popper) สำหรับ Modal --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ดักคลิกปุ่ม/ลิงก์ที่ต้องล็อกอินก่อน: ใช้ data-auth="required" + data-auth-message --}}
    <script>
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('[data-auth="required"]');
            if (!trigger) return;

            e.preventDefault();
            e.stopPropagation();

            const title = trigger.getAttribute('data-auth-title') ||
                'โปรดเข้าสู่ระบบก่อนดำเนินการ';
            const msg = trigger.getAttribute('data-auth-message') ||
                'เพื่อดำเนินการสั่งซื้อหรือเพิ่มสินค้าในตะกร้า กรุณาเข้าสู่ระบบ หรือสมัครสมาชิกก่อนดำเนินการต่อ';

            const titleEl = document.getElementById('authRequiredTitle');
            const textEl = document.getElementById('authRequiredText');
            if (titleEl) titleEl.lastChild.nodeValue = ' ' + title; // คงไอคอน ซ้อนชื่อเรื่อง
            if (textEl) textEl.innerHTML = msg;

            const modalEl = document.getElementById('authRequiredModal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        });
    </script>

</body>

</html>
