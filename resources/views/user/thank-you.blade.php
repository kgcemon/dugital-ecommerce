@extends('user.master')

@section('title', "Order Placed")

@section('content')

    <style>
        .order-summary {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-width: 650px;
            margin: 20px auto;
        }
        .summary-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 18px;
            gap: 15px;
        }
        .summary-row img {
            border-radius: 10px;
            max-width: 90px;
            max-height: 90px;
            object-fit: cover;
        }
        .summary-info p {
            margin: 4px 0;
            font-size: 15px;
            color: #333;
        }
        .summary-info strong {
            color: #000;
        }
        .selection-title h3 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 18px;
            color: #2d2d2d;
        }
    </style>

    <div class="order-summary">
        <div class="selection-title">
            <h3>Thank You!</h3>
        </div>

        {{-- Product Row --}}
        <div class="summary-row">
            <img src="{{$order->product->image ?? 'default.png'}}" alt="Product Image">
            <div class="summary-info">
                <p><strong>Order ID:</strong> {{$order->id}}</p>
                <p><strong>Items:</strong> {{$order->item->name ?? $order->product->name}}</p>
                <p><strong>Total Paid:</strong> {{$order->total}}৳</p>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="summary-row">
            <div class="summary-info">
                <p><strong>{{$order->product->input_name}}:</strong> {{$order->customer_data}}</p>
            </div>
        </div>

        {{-- Payment Row --}}
        <div class="summary-row">
            <img src="{{ asset('images/payment/'.$order->paymentMethod->icon) }}" alt="{{$order->paymentMethod->method}}">
            <div class="summary-info">
                <p><strong>Method:</strong> {{$order->paymentMethod->method}}</p>
                <p><strong>Number:</strong> {{$order->transaction_id ?? ''}}</p>
                <p><strong>TrxID:</strong> {{$order->number ?? ''}}</p>
            </div>
        </div>
    </div>

@endsection
