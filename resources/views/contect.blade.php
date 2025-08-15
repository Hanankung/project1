@extends('layout')

@section('content')
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sero-Secret - ติดต่อเรา</title>
        <!-- เรียกใช้ Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/contect.css') }}">
    </head>

    <body>

        <div class="container">
            <h3>ติดต่อเรา 🌿</h3>
            <div class="contact-info">
                <p>แหล่งเรียนรู้ส่งเสริมอาชีพ กลุ่มอาชีพสืบสาน กลุ่มวิสาหกิจชุมชน</p>
                <p>by Siro-Secret</p>
                <p>📞 097-3502899</p>
                <p>📍 13 หมู่ที่ 2 ตำบลป่าบอน อำเภอป่าบอน จังหวัดพัทลุง</p>
            </div>
            <br>

            <div class="social-box">
                <a href="#" class="facebook"><i class="fab fa-facebook"></i> Siro Secret</a>
                <a href="mailto:sirosecret@email.com" class="email"><i class="fas fa-envelope"></i>
                    sirosecret@email.com</a>
                <a href="#" class="line"><i class="fab fa-line"></i> @sirosecret</a>
            </div>
            <div class="support-section">
                <strong>สนับสนุนโดย :</strong>
                <div class="support-logos">
                    <img src="{{ asset('image/logo1.png') }}" alt="Logo1">
                    <img src="{{ asset('image/logo2.png') }}" alt="Logo2">
                    <img src="{{ asset('image/logo3.png') }}" alt="Logo3">
                    <img src="{{ asset('image/logo4.png') }}" alt="Logo4">
                    <img src="{{ asset('image/logo5.png') }}" alt="Logo5">
                    <img src="{{ asset('image/logo6.png') }}" alt="Logo6">
                    <img src="{{ asset('image/logo7.png') }}" alt="Logo7">
                    <img src="{{ asset('image/logo8.png') }}" alt="Logo8">
                    <img src="{{ asset('image/logo9.png') }}" alt="Logo9">
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection
