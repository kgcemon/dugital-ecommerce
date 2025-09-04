@extends('user.master')

@section('title', "Order Placed")

@section('content')

<div class="container">
    <div class="selection-panel completed" data-step="1">
        <div class="selection-title"> <h3>Thank You!</h3>
           </div>
        <div class="des">
            <p><strong>Order ID:</strong> {{$order->id}}</p>
            <p><strong>Items:</strong> {{$order->item->name ?? $order->product->name}}</p>
            <p><strong>Total Paid:</strong>{{$order->total}}৳</p>
        </div>
        <div class="des">
            <p><strong>{{$order->product->input_name}}</strong>{{$order->customer_data}}</p>
        </div>
        <div class="des">
            <p><strong>Method:</strong>{{$order->paymentMethod->method}}</p>
            <p><strong>Number:</strong> {{$order->transaction_id ?? ''}}</p>
            <p><strong>TrxID: </strong> {{$order->number ?? ''}}</p>
        </div>
    </div>
</div>

@endsection
