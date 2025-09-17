<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Siro-Secret') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    {{-- ⭐ พื้นหลังไล่เฉดโทนอุ่น + ไม่ใช้ bg-gray-100 แล้ว --}}
    <body class="font-sans antialiased bg-gradient-to-b from-amber-50 to-white min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0">

            {{-- โลโก้แบบข้อความแบรนด์ --}}
            <div class="text-center">
                <a href="/" aria-label="ไปหน้าแรก" class="select-none">
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-emerald-700">
                        Siro-Secret
                    </div>
                    <div class="mt-1 text-sm text-emerald-800/80">
                        ผ้าพิมพ์ลายธรรมชาติ ด้วยเทคนิค Eco Print
                    </div>
                </a>
            </div>

            {{-- การ์ดฟอร์ม --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
