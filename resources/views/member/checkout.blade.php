@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    
    <div class="container my-5">
        <a href="{{ route('cart.store') }}" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
        <h2 class="mb-4">{{ __('messages.Order Confirmation') }}</h2>

        {{-- แสดง Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- แสดง Alert ถ้ามี --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            {{-- ฟอร์มข้อมูลการจัดส่ง --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        {{ __('messages.Recipient information') }}</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.Recipient name') }}</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', auth()->user()->name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('messages.Shipping address') }}</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('messages.Phone') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                    required>
                            </div>

                            <button type="submit"
                                class="btn btn-success w-100">{{ __('messages.Order Confirmation') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- แสดงรายละเอียดสินค้า --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        {{ __('messages.Items in Cart') }}
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $locale = app()->getLocale();
                                                if ($locale === 'th') {
                                                    echo $item->product->product_name;
                                                } elseif ($locale === 'en') {
                                                    echo $item->product->product_name_ENG;
                                                } elseif ($locale === 'ms') {
                                                    echo $item->product->product_name_MS;
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->product->price * $item->quantity, 2) }}
                                            ฿</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">{{ __('messages.Total price') }}</th>
                                    <th class="text-end">{{ number_format($total, 2) }} ฿</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
