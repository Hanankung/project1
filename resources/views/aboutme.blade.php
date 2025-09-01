@extends('layout')

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
            <h1>{{ __('messages.about_me') }}</h1>
            <div class="container">
                <div class="about-box"> <img src="{{ asset('image/aboutMe1.jpg') }}" alt="ร้าน Siro-Secret">
                    <div class="about-info">
                        <p><b>{{ __('messages.Shop Name') }} :</b>
                            {{ __('messages.Shop Name 1') }}</p>
                        <br>
                        <p><b>{{ __('messages.Store details') }} :</b>{{ __('messages.details') }} </p>
                    </div>
                </div>
                <div class="award-title">{{ __('messages.Our pride') }} : {{ __('messages.Certificates') }}</div>
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
        </div>
    </body>

    </html>
@endsection
