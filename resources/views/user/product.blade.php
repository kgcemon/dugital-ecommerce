@extends('user.master')

@section('title', "$product->name Top Up Bangladesh")

@section('content')

    <div class="container">
        <!-- Product Card -->
        <div class="product-card">
            <div class="product-thumb">
                <img src="{{$product->image}}" alt="Uid Topup">
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
                    <div class="payment-option"
                         data-id="{{ $method->id }}"
                         data-method="{{ $method->method }}"
                         data-number="{{ $method->number }}"
                         data-description="{{ $method->description }}">
                        <img src="{{ $method->icon }}" alt="{{ $method->method }}" style="height:25px; margin-right:5px;">
                        {{ $method->method }}
                    </div>
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
    <script>
        const diamondOptions = document.querySelectorAll(".diamond-option");
        let selectedPackage = null;
        let selectedPayment = null;

        function updateSteps(){
            const pid = document.getElementById("playerId").value.trim();
            document.getElementById("step1").classList.toggle("completed", pid.length >= 6 && pid.length <= 13);
            document.getElementById("step2").classList.toggle("completed", !!selectedPackage);
            document.getElementById("step3").classList.toggle("completed", !!selectedPayment);
        }

        function init(){
            // ✅ Diamond package selection
            diamondOptions.forEach(el=>{
                el.onclick = () => {
                    diamondOptions.forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");
                    selectedPackage = +el.dataset.id;
                    document.getElementById("packageError").textContent = "";
                    updateSteps();
                }
            });

            // ✅ Dynamic payment method selection
            const paymentOptions = document.querySelectorAll(".payment-option");
            paymentOptions.forEach(el=>{
                el.onclick = () => {
                    paymentOptions.forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");

                    selectedPayment = {
                        id: el.dataset.id,
                        method: el.dataset.method,
                        number: el.dataset.number,
                        description: el.dataset.description
                    };

                    document.getElementById("paymentDetails").innerHTML =
                        `<p><strong>${selectedPayment.method}</strong></p>
                     <p>${selectedPayment.description}</p>
                     <p><strong>Number:</strong> ${selectedPayment.number}</p>
                     <p>After payment, note TRX ID & Submit.</p>`;

                    updateSteps();
                }
            });

            // ✅ Auto-select first payment option
            if(paymentOptions.length > 0){
                paymentOptions[0].click();
            }

            // ✅ Player ID validation
            document.getElementById("playerId").addEventListener("input", updateSteps);

            // ✅ Submit Order
            document.getElementById("checkoutBtn").onclick = () => {
                const pid = document.getElementById("playerId").value.trim();
                const trxId = document.getElementById("trxId").value.trim();
                let valid = true;

                if(!(pid.length >= 6 && pid.length <= 13)){
                    document.getElementById("playerError").textContent = "Player ID must be 6-13 digits!";
                    valid = false;
                } else {
                    document.getElementById("playerError").textContent = "";
                }

                if(!selectedPackage){
                    document.getElementById("packageError").textContent = "অনুগ্রহ করে ডায়মন্ড প্যাকেজ নির্বাচন করুন!";
                    valid = false;
                }

                if(!selectedPayment){
                    alert("অনুগ্রহ করে পেমেন্ট মেথড নির্বাচন করুন!");
                    valid = false;
                }

                if(trxId.length < 5){
                    document.getElementById("trxError").textContent = "Valid Transaction ID দিন!";
                    valid = false;
                } else {
                    document.getElementById("trxError").textContent = "";
                }

                if(!valid) return;

                const pkg = Array.from(diamondOptions).find(p => +p.dataset.id === selectedPackage);

                // ✅ Send data (AJAX or form submission)
                alert(`✅ Order Submitted!\n\nPlayer ID: ${pid}\nPackage ID: ${selectedPackage}\nPayment Method ID: ${selectedPayment.id}\nPayment: ${selectedPayment.method}\nTRX ID: ${trxId}\nAmount: ${pkg.querySelector(".price").textContent}\nSend To: ${selectedPayment.number}`);
            }
        }

        document.addEventListener("DOMContentLoaded", init);
    </script>
@endpush
