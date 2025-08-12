<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('css/admindashboard.css') }}">
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

            </div>
            <div class="icon-right">
                <i class="fas fa-calendar icon"></i>
                <a href="#" class="fas fa-user icon"></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            <a href="/admin/product">สินค้า</a>
            <a href="/admin/course">คอร์สเรียน</a>
        </div>
    </div>

    <div class="ecoimg">
        <img src="{{ asset('image/AdminDashboard1.jpg') }}" alt="Eco Print Banner" class="eco-banner">
    </div>
    <div class="hero">
        <p>WELCOME TO ADMIN</p>
    </div>

    <div class="image-card">
        <img src="{{ asset('image/snim.jpg') }}" alt="Eco Print Banner" class="eco-banner">
    </div>
    </div>


</body>

</html>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button onclick="event.preventDefault(); this.closest('form').submit();">logout</button>

</form>
