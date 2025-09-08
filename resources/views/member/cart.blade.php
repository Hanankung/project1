@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .cart-table img {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: 8px;
            }

            .quantity-input {
                width: 70px;
                text-align: center;
            }

            .btn-remove {
                background-color: #dc3545;
                color: #fff;
                border: none;
                padding: 6px 12px;
                border-radius: 6px;
            }

            .btn-remove:hover {
                background-color: #c82333;
            }

            .btn-order {
                background-color: #007bff;
                color: #fff;
                font-size: 18px;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
            }

            .btn-order:hover {
                background-color: #0069d9;
                color: #fff;
            }
        </style>
    </head>

    <div class="container mt-4">
        <h2 class="mb-4">{{ __('messages.Shopping cart') }}</h2>

        @if (count($cartItems) > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle cart-table">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.product name') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.Total price') }}</th>
                            <th>{{ __('messages.delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $subtotal = $item->product->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ asset($item->product->product_image) }}"
                                        alt="{{ $item->product->product_name }}">
                                </td>
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
                                <td>{{ number_format($item->product->price, 2) }} {{ __('messages.baht') }}</td>
                                <td>
                                    <form action="{{ route('member.cart.update', $item->id) }}" method="POST"
                                        class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="form-control quantity-input me-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ number_format($subtotal, 2) }} {{ __('messages.baht') }}</td>
                                <td>
                                    <form action="{{ route('member.cart.delete', $item->id) }}" method="POST"
                                        onsubmit="return confirm(@js(__('messages.confirm_delete')));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- สรุปราคารวม -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>{{ __('messages.Total price') }}: <strong>{{ number_format($total, 2) }}
                        {{ __('messages.baht') }}</strong></h4>
                {{-- ปุ่มสั่งซื้อ (ไปหน้า checkout) --}}
                <form action="{{ route('checkout.from_cart') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-order">
                        <i class="bi bi-bag-check"></i> {{ __('messages.buy_now') }}
                    </button>
                </form>


            </div>
        @else
            <div class="alert alert-info text-center">
                {{ __('messages.warn2') }}
            </div>
        @endif

    </div>
@endsection
