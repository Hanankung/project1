<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Siro-Secret | ผ้าพิมพ์ลายธรรมชาติ Eco Print</title>
    <link rel="stylesheet" href="{{ asset('CSS/admindashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- ✅ ใส่ Bootstrap ตรงนี้ --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</head>

<body>
    {{-- Top Bar --}}
    <div class="top-bar">
        <div class="brand">
            <div class="brand-title">Siro-Secret</div>
            <div class="brand-subtitle">ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print</div>
        </div>
        <div class="top-right">
            <div class="search-center"><!-- reserved --></div>
            <div class="icon-right">
                <a href="{{ route('admin.course.booking.index') }}" class="fas fa-calendar icon"></a>

                {{-- Dropdown ผู้ใช้ --}}
                <div class="dropdown position-relative">
                    <a href="#" class="fas fa-user icon" id="userIcon"></a>
                    <div class="dropdown-content" id="dropdownMenu">
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                    </a>
                    </div>
                </div>
            </div>

            {{-- ฟอร์ม logout --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                </form>

            <script>
                const userIcon = document.getElementById('userIcon');
                const dropdownMenu = document.getElementById('dropdownMenu');
                userIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('show');
                });
                window.addEventListener('click', function(e) {
                    if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) dropdownMenu.classList.remove(
                        'show');
                });
            </script>
        </div>
    </div>

    {{-- สร้างแฟล็กแอคทีฟตาม route/path --}}
    @php
        // กลุ่มสินค้า
        $isProduct =
            request()->routeIs(
                'admin.product',
                'admin.show',
                'admin.edit',
                'admin.update',
                'admin.delete',
                'create',
                'store',
            ) ||
            request()->is('admin/product*') ||
            request()->is('admin/create') ||
            request()->is('admin/edit/*');

        // กลุ่มคอร์ส
        $isCourse =
            request()->routeIs(
                'admin.course',
                'admin.showcourse',
                'admin.edit_course',
                'admin.update_course',
                'admin.delete_course',
                'create_course',
                'store_course',
            ) ||
            request()->is('admin/course*') ||
            request()->is('admin/create_course');

        // กลุ่มคำสั่งซื้อ
        $isOrders = request()->routeIs('admin.orders.*') || request()->is('admin/orders*');
    @endphp

    <div class="nav-bar">
        <div class="nav-center">
            <a href="{{ route('admin.product') }}" class="nav-tab {{ $isProduct ? 'active' : '' }}">สินค้า</a>
            <a href="{{ route('admin.course') }}" class="nav-tab {{ $isCourse ? 'active' : '' }}">คอร์เรียน</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-tab {{ $isOrders ? 'active' : '' }}">คำสั่งซื้อ</a>
        </div>
    </div>


    <div class="container mt-5">
        @yield('content')
    </div>
    {{-- SweetAlert2 --}}
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function() {
            // ===== Modal กลางจอ =====
            function showCenter(icon, title, text) {
                Swal.fire({
                    icon,
                    title,
                    text,
                    position: 'center',
                    showConfirmButton: true,
                    confirmButtonText: 'ตกลง',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                    // ไม่ใส่ timer เพื่อให้ผู้ใช้กดปิดเอง (ถ้าจะให้ปิดเอง เติม timer:1800 ได้)
                });
            }

            // ====== ใช้ค่าจาก session ======
                @if (session('success'))
                    showCenter('success', 'สำเร็จ', @json(session('success')));
            @endif

                @if (session('error'))
                    showCenter('error', 'ไม่สำเร็จ', @json(session('error')));
            @endif

                @if (session('warning'))
                    showCenter('warning', 'แจ้งเตือน', @json(session('warning')));
            @endif

            // Validation error กรณีมี
                @if ($errors->any())
                    showCenter('error', 'ข้อมูลไม่ถูกต้อง', @json($errors->first()));
            @endif
        })();
    </script>

    {{-- ยืนยันการลบ แบบ SweetAlert2 (แทน confirm()) --}}
        <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-confirm-delete]');
            if (!btn) return;

            e.preventDefault();
                const form = btn.closest('form');
            Swal.fire({
                icon: 'question',
                title: 'ลบสินค้านี้หรือไม่?',
                text: 'การลบไม่สามารถย้อนกลับได้',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#e03131'
            }).then((res) => {
                if (res.isConfirmed) form.submit();
            });
        });
    </script>

    </body>

</html>
