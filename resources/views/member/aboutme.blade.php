@extends('member.layout')

@section('content')
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Siro-Secret - เกี่ยวกับเรา</title>
        <link rel="stylesheet" href="{{ asset('css/aboutme.css') }}">
    </head>

    <body>
        <div class="header">
            <h1>เกี่ยวกับเรา</h1>
        <div class="container">
            <div class="about-box"> <img src="{{ asset('image/aboutMe1.jpg') }}" alt="ร้าน Siro-Secret">
                <div class="about-info">
                    <p><b>ชื่อร้านค้า:</b> Siro-Secret - เสริมโชค เสริมทรัพย์ สักทอง ของขวัญจากธรรมชาติ
                        ผ้าพิมพ์ลายธรรมชาติด้วยเทคนิค Eco Print</p>
                        <br>
                    <p><b>รายละเอียดร้านค้า:</b> จำหน่ายผ้าพิมพ์ลายธรรมชาติ (Eco Print) จากจังหวัดพัทลุง
                        สินค้าทำมือจากใบไม้จริง ผ้าพันคอ เสื้อผ้า และของตกแต่ง เสริมพลังชีวิตด้วยผลงานจากธรรมชาติแท้ 100%
                        โดยช่างฝีมือท้องถิ่น</p>
                </div>
            </div>
            <div class="award-title">ความภูมิใจของเรา: หนังสือรับรองและรางวัลแห่งคุณค่า</div>
            <div class="award-gallery"> 
                <img src="{{ asset('image/reward1.jpg') }}" alt="รางวัล 1"> 
                <img src="{{ asset('image/reward2.jpg') }}" alt="รางวัล 2"> 
                <img src="{{ asset('image/reward3.jpg') }}" alt="รางวัล 3"> 
                <img src="{{ asset('image/reward4.jpg') }}" alt="รางวัล 4"> 
                <img src="{{ asset('image/reward5.jpg') }}" alt="รางวัล 5"> 
                <img src="{{ asset('image/reward6.jpg') }}" alt="รางวัล 6"> 
            </div>
            <div class="award-gallery"> 
                <img src="{{ asset('image/reward7.jpg') }}" alt="รางวัล 1"> 
                <img src="{{ asset('image/reward8.jpg') }}" alt="รางวัล 2"> 
                <img src="{{ asset('image/reward9.jpg') }}" alt="รางวัล 3"> 
                <img src="{{ asset('image/reward10.jpg') }}" alt="รางวัล 4"> 
                <img src="{{ asset('image/reward11.jpg') }}" alt="รางวัล 5"> 
                <img src="{{ asset('image/reward12.jpg') }}" alt="รางวัล 6"> 
            </div>
        </div>
    </body>

    </html>
@endsection
