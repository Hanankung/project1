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
                <input type="text" class="search-input" placeholder="ค้นหา...">
                <button class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <i class="fas fa-language icon"></i>
            </div>
            <div class="icon-right">
                <i class="fas fa-shopping-cart icon"></i>
                <i class="fas fa-calendar icon"></i>
                <a href="/profile" class="fas fa-user icon"></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            <a href="/member/product">สินค้า</a>
            <a href="/member/courses">คอร์สเรียน</a>
            <a href="/member/aboutme">เกี่ยวกับเรา</a>
        </div>
        <div class="nav-right">
            <a href="/member/contect">ติดต่อเรา</a>
        </div>
    </div>
    <div class="container mt-5">
        @yield('content')
    </div>
</body>    