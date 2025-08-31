@extends('user.master')

@section('title', "$product->name")

@section('content')

    <div class="container">

        <!-- ✅ Order Response Box -->
        <div id="orderResponse" style="display:none;" class="response-box"></div>

        <!-- ✅ Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner" style="display:none;">
            <div class="spinner"></div>
            <p>অর্ডার প্রক্রিয়াধীন...</p>
        </div>

        <!-- Product Card -->
        <div class="product-card">
            <div class="product-thumb">
                <img src="/{{$product->image}}" alt="Uid Topup">
            </div>
            <div class="product-details">
                <h1>{{$product->name}}</h1>
                <span class="product-subtitle">{{ "শুধু মাত্র " .$product->support_country. " সার্ভারে"}}</span>
                <br>
                <span class="product-subtitle">{{ "ডেলিভারি " .$product->delivery_system}}</span>
            </div>
        </div>

        <!-- Step 1: Player ID -->
        <div class="selection-panel" data-step="1" id="step1">
            <div class="player-id-box">
                <h2 class="selection-title">Player ID লিখুন</h2>
                <input type="text" id="playerId" placeholder="Enter your Player ID">
                <div class="error-message" id="playerError"></div>
            </div>
        </div>

        <!-- Step 2: Package Selection -->
        <div class="selection-panel" data-step="2" id="step2">
            <h2 class="selection-title">প্যাকেজ নির্বাচন করুন</h2>
            <div class="diamond-options" id="diamondOptions">
                @foreach($product['items'] as $item)
                    <div class="diamond-option" data-id="{{ $item['id'] }}">
                        <span>{{ $item['name'] }}</span>
                        <span class="price">{{ $item['price'] }}৳</span>
                    </div>
                @endforeach
            </div>
            <div class="error-message" id="packageError"></div>
        </div>

        <!-- Step 3: Payment Selection -->
        <div class="selection-panel" data-step="3" id="step3">
            <h2 class="selection-title">পেমেন্ট পদ্ধতি নির্বাচন করুন</h2>
            <div class="payment-methods">
                @foreach($payment as $method)
                    @if($method->method === 'Wallet')
                        @auth
                            <div class="payment-option wallet-option"
                                 data-id="{{ $method->id }}"
                                 data-number="{{ $method->number }}"
                                 data-method="{{ $method->method }}"
                                 data-description="{{ $method->description }}">
                                <img src="{{ $method->icon }}" alt="{{ $method->method }}" style="height:25px; margin-right:5px;">
                                {{ $method->method }} -
                                <span style="font-weight:600; color:#007bff;">
                            {{ Auth::user()->wallet ?? 0 }}৳
                        </span>
                            </div>
                        @endauth
                    @else
                        <div class="payment-option"
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

            <!-- TRX ID input -->
            <div class="player-id-box">
                <h2 class="selection-title">Transaction ID লিখুন</h2>
                <input type="text" id="trxId" placeholder="Enter Transaction ID">
                <div class="error-message" id="trxError"></div>
            </div>
        </div>



        <!-- Submit Button -->
        <button class="checkout-btn" id="checkoutBtn">Submit Order</button>

        <div><br></div>

        <!-- Rules -->
        <div class="des">
            <h2 class="selection-title">Rules & Conditions</h2>
            <div class="payment-details"> {!! $product->description !!} </div>
        </div>

        <div><br><br></div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/user/product.js') }}"></script>
@endpush
