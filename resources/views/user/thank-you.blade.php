@extends('user.master')

@section('title', "Order Placed")

@section('content')

<div class="container">
    <div class="selection-panel completed" data-step="1">
        <div class="selection-title"> <h3>Thank You!</h3>
           </div>
        <div class="des">
            <p><strong>Order ID:</strong> #123456</p>
            <p><strong>Items:</strong> 3 Products</p>
            <p><strong>Total Paid:</strong> $89.99</p>
        </div>
        <div class="des">
            <p><strong>Name:</strong> John Doe</p>
            <p><strong>Address:</strong> 123 Main Street, City, Country</p>
            <p><strong>Estimated Delivery:</strong> 3-5 Business Days</p>
        </div>
        <div class="des">
            <p>Credit/Debit Card (**** **** **** 1234)</p>
        </div>
    </div>
</div>

@endsection
