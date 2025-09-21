<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('CSS/admindashboard.css') }}">
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
                <a href="{{ route('admin.course.booking.index') }}" class="fas fa-calendar icon"></a>

                <!-- Dropdown ผู้ใช้ -->
                <div class="dropdown">
                    <a href="#" class="fas fa-user icon" id="userIcon"></a>
                    <div class="dropdown-content" id="dropdownMenu">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- ฟอร์ม logout สำหรับ Laravel -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <!-- JS -->
            <script>
                const userIcon = document.getElementById('userIcon');
                const dropdownMenu = document.getElementById('dropdownMenu');

                userIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('show');
                });

                // ปิด dropdown ถ้าคลิกนอก
                window.addEventListener('click', function(e) {
                    if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            </script>

        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-center">
            <a href="{{ route('admin.product') }}">สินค้า</a>
            <a href="{{ route('admin.course') }}">คอร์สเรียน</a>
            <a href="{{ route('admin.orders.index') }}"> คำสั่งซื้อ</a>
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

{{-- <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button onclick="event.preventDefault(); this.closest('form').submit();">logout</button>

</form> --}}
