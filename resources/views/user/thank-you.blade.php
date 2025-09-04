@extends('user.master')

@section('title', "Order Placed")

@section('content')

    <div class="container">
        <div class="selection-panel completed" data-step="1">
            <div class="selection-title">
                <h3>Thank You!</h3>
            </div>

            <div class="row align-items-center mb-3">
                {{-- Product Image --}}
                <div class="col-4 text-center">
                    <img src="{{ asset($order->product->image ?? 'default.png') }}"
                         alt="Product Image"
                         style="max-width: 100px; border-radius: 10px;">
                </div>

                {{-- Order Info --}}
                <div class="col-8">
                    <p><strong>Order ID:</strong> {{$order->id}}</p>
                    <p><strong>Items:</strong> {{$order->item->name ?? $order->product->name}}</p>
                    <p><strong>Total Paid:</strong> {{$order->total}}৳</p>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="des mb-3">
                <p><strong>{{$order->product->input_name}}:</strong> {{$order->customer_data}}</p>
            </div>

            {{-- Payment Info with Icon --}}
            <div class="row align-items-center">
                <div class="col-4 text-center">
                    <img src="{{$order->paymentMethod->icon}}"
                         alt="{{$order->paymentMethod->method}}"
                         style="max-width: 70px;">
                </div>
                <div class="col-8">
                    <p><strong>Method:</strong> {{$order->paymentMethod->method}}</p>
                    <p><strong>Number:</strong> {{$order->transaction_id ?? ''}}</p>
                    <p><strong>TrxID:</strong> {{$order->number ?? ''}}</p>
                </div>
            </div>

        </div>
    </div>

@endsection
