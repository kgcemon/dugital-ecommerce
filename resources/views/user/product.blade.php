@extends('user.master')

@section('title', 'Premium Diamond Packages')

@section('content')

    <div class="container">

        <div class="product-card">
            <div class="product-thumb">
                <img src="{{$product->image}}" alt="Uid Topup">
            </div>
            <div class="product-details">
                <h3 class="title">{{$product->name}}</h3>
                <span class="product-subtitle">{{ "শুধু মাত্র " .$product->support_country. " সার্ভারে"}}</span>
                <br>
                <span class="product-subtitle">{{ "ডেলিভারি " .$product->delivery_system}}</span>
            </div>
        </div>

        <div class="selection-panel" data-step="1" id="step1">
            <div class="player-id-box">
                <h2 class="selection-title">Player ID লিখুন</h2>
                <input type="text" id="playerId" placeholder="Enter your Player ID">
                <div class="error-message" id="playerError"></div>
            </div>
        </div>

        <div class="selection-panel" data-step="2" id="step2">
            <h2 class="selection-title">ডায়মন্ড প্যাকেজ নির্বাচন করুন</h2>
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

        <div class="selection-panel" data-step="3" id="step3">
            <h2 class="selection-title">পেমেন্ট পদ্ধতি নির্বাচন করুন</h2>
            <div class="payment-methods">
                <div class="payment-option" data-method="Bkash">Bkash</div>
                <div class="payment-option" data-method="Nagad">Nagad</div>
                <div class="payment-option" data-method="Rocket">Rocket</div>
            </div>
            <div class="payment-details" id="paymentDetails"></div>
        </div>
        <button class="checkout-btn" id="checkoutBtn">Submit Order</button>
        <div> <br> <br> <br></div>
    </div>
@endsection

@push('scripts')
    <script>
        const diamondOptions = document.querySelectorAll(".diamond-option");
        let selectedPackage = null;
        let selectedPayment = "Bkash";

        const paymentInstructions = {
            "Bkash":"Send payment to Bkash Number: <strong>0123456789</strong>",
            "Nagad":"Send payment to Nagad Number: <strong>0987654321</strong>",
            "Rocket":"Send payment to Rocket Number: <strong>0112233445</strong>"
        };

        function updateSteps(){
            const pid = document.getElementById("playerId").value.trim();
            document.getElementById("step1").classList.toggle("completed", pid.length >= 6 && pid.length <= 13);
            document.getElementById("step2").classList.toggle("completed", !!selectedPackage);
            document.getElementById("step3").classList.toggle("completed", !!selectedPayment);
        }

        function init(){
            diamondOptions.forEach(el=>{
                el.onclick = () => {
                    diamondOptions.forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");
                    selectedPackage = +el.dataset.id;
                    document.getElementById("packageError").textContent = "";
                    updateSteps();
                }
            });

            document.querySelectorAll(".payment-option").forEach(el=>{
                el.onclick = () => {
                    document.querySelectorAll(".payment-option").forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");
                    selectedPayment = el.dataset.method;
                    document.getElementById("paymentDetails").innerHTML = paymentInstructions[selectedPayment]+"<br>After payment, note TRX ID & Submit.";
                    updateSteps();
                }
            });

            document.querySelector(`[data-method="${selectedPayment}"]`).classList.add("selected");
            document.getElementById("paymentDetails").innerHTML = paymentInstructions[selectedPayment];

            document.getElementById("playerId").addEventListener("input", updateSteps);

            document.getElementById("checkoutBtn").onclick = () => {
                const pid = document.getElementById("playerId").value.trim();
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
                if(!valid) return;

                const pkg = Array.from(diamondOptions).find(p => +p.dataset.id === selectedPackage);
                alert(`✅ Order Submitted!\n\nPlayer ID: ${pid}\nPackage: ${pkg.querySelector("span").textContent}\nPayment: ${selectedPayment}\nAmount: ${pkg.querySelector(".price").textContent}`);
            }
        }

        document.addEventListener("DOMContentLoaded", init);
    </script>
@endpush
