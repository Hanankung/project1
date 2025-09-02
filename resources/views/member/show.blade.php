@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    </head>

    <div class="container mt-5">
        </a>
        {{-- ถ้าเป็นรายการออเดอร์ทั้งหมด --}}
        {{-- ถ้าเป็นรายการออเดอร์ทั้งหมด --}}
        @if (isset($orders))
            <h2>{{ __('messages.Order History') }}</h2>
            @if ($orders->isEmpty())
                <div class="alert alert-info mt-3">{{ __('messages.warn1') }}</div>
            @else
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Order date') }}</th>
                            <th>{{ __('messages.Total price') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.detail') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total_price, 2) }} {{ __('messages.baht') }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>
                                    <a href="{{ route('member.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        {{ __('messages.description') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- ถ้าเป็นรายละเอียดออเดอร์ตัวเดียว --}}
        @elseif(isset($order))
            <h2>{{ __('messages.Order details') }} #{{ $order->id }}</h2>
            <p><strong>{{ __('messages.Buyer') }}:</strong> {{ $order->name }}</p>
            <p><strong>{{ __('messages.Address') }}:</strong> {{ $order->address }}</p>
            <p><strong>{{ __('messages.Phone') }}:</strong> {{ $order->phone }}</p>
            <p><strong>{{ __('messages.status') }}:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>{{ __('messages.Total price') }}:</strong> {{ number_format($order->total_price, 2) }}
                {{ __('messages.baht') }}</p>
            <br>
            <h4 class="mt-4">{{ __('messages.Product List') }}</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.price') }}/{{ __('messages.Piece') }}</th>
                        <th>{{ __('messages.Total price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
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
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            <a href="{{ route('member.orders') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
            </a>
        @endif


    </div>
@endsection
