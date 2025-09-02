@extends('user.master')

@section('title', "Deposit")

@section('content')
<style>
    .body{
        padding: 25px;
    }

</style>
<!-- Step 3: Payment Selection -->
<div class="selection-panel body" data-step="3" id="step3">
    <h2 class="selection-title">পেমেন্ট পদ্ধতি নির্বাচন করুন</h2>

    <div class="payment-methods" style="display:flex; flex-wrap:wrap; gap:10px;">
        @foreach($payment as $method)
            @if($method->method === 'Wallet')
                @auth
                    <div class="payment-option wallet-option"
                         style="flex:1 1 calc(33.333% - 10px); padding:10px; border:1px solid #ccc; border-radius:8px; cursor:pointer;"
                         data-id="{{ $method->id }}"
                         data-number="{{ $method->number }}"
                         data-method="{{ $method->method }}"
                         data-description="{{ $method->description }}">
                        <img src="{{ $method->icon }}" alt="{{ $method->method }}" style="height:25px; margin-right:5px;">
                        {{ $method->method }} <br>
                        <span style="font-weight:600; color:#fff;">
                                {{ Auth::user()->wallet ?? 0 }}৳
                            </span>
                    </div>
                @endauth
            @else
                <div class="payment-option"
                     style="flex:1 1 calc(33.333% - 10px); padding:10px; border:1px solid #ccc; border-radius:8px; cursor:pointer;"
                     data-id="{{ $method->id }}"
                     data-number="{{ $method->number }}"
                     data-method="{{ $method->method }}"
                     data-description="{{ $method->description }}">
                    <img src="{{ $method->icon }}" alt="{{ $method->method }}" style="height:25px; margin-right:5px;">
                    {{ $method->method }}
                </div>
            @endif
        @endforeach
    </div>

    <div class="payment-details" id="paymentDetails"></div>

    <!-- Transaction ID input -->
    <div id="trxBox" class="player-id-box" style="display:none;">
        <h2 class="selection-title">Transaction ID লিখুন</h2>
        <input type="text" id="trxId" placeholder="Enter Transaction ID">
    </div>

    <!-- Payment Number input -->
    <div id="paymentNumberBox" class="player-id-box" style="display:none;">
        <h2 class="selection-title">Payment Number লিখুন</h2>
        <input type="text" id="paymentNumber" placeholder="Enter Payment Number">
    </div>
    <br>
    <!-- Submit Button -->
    <button class="checkout-btn" id="checkoutBtn">Submit Order</button>
</div>

@endsection
