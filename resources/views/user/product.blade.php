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

            <!-- Transaction ID input (hidden by default for Wallet) -->
            <div id="trxBox" class="player-id-box" style="display:none;">
                <h2 class="selection-title">Transaction ID লিখুন</h2>
                <input type="text" id="trxId" placeholder="Enter Transaction ID">
                <div class="error-message" id="trxError"></div>
            </div>

            <!-- Payment Number input (hidden by default, only shown if needed) -->
            <div id="paymentNumberBox" class="player-id-box" style="display:none;">
                <h2 class="selection-title">Payment Number লিখুন</h2>
                <input type="text" id="paymentNumber" placeholder="Enter Payment Number">
                <div class="error-message" id="paymentNumberError"></div>
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
        document.addEventListener("DOMContentLoaded", () => {
            let selectedPackage = null;
            let selectedPayment = null;

            const diamondOptions = document.querySelectorAll(".diamond-option");
            const paymentOptions = document.querySelectorAll(".payment-option");

            const playerIdInput = document.getElementById("playerId");
            const trxBox = document.getElementById("trxBox");
            const trxIdInput = document.getElementById("trxId");
            const paymentNumberBox = document.getElementById("paymentNumberBox");
            const paymentNumberInput = document.getElementById("paymentNumber");

            const checkoutBtn = document.getElementById("checkoutBtn");
            const orderResponse = document.getElementById("orderResponse");
            const loadingSpinner = document.getElementById("loadingSpinner");

            function showResponse(type, message) {
                orderResponse.style.display = "block";
                orderResponse.className = "response-box " + type;
                orderResponse.innerHTML = message;
                loadingSpinner.style.display = "none";
                checkoutBtn.disabled = false;
                window.scrollTo({ top: 0, behavior: "smooth" });
            }

            function showLoading() {
                loadingSpinner.style.display = "flex";
                orderResponse.style.display = "none";
            }

            // Package selection
            diamondOptions.forEach(el => {
                el.addEventListener("click", () => {
                    diamondOptions.forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");
                    selectedPackage = parseInt(el.dataset.id, 10);
                    document.getElementById("packageError").textContent = "";
                });
            });

            // Payment method selection
            paymentOptions.forEach(el => {
                el.addEventListener("click", () => {
                    paymentOptions.forEach(o => o.classList.remove("selected"));
                    el.classList.add("selected");

                    selectedPayment = {
                        id: parseInt(el.dataset.id, 10),
                        method: el.dataset.method,
                        number: el.dataset.number,
                        description: el.dataset.description
                    };

                    document.getElementById("paymentDetails").innerHTML = `
                <p><strong>${selectedPayment.method}</strong></p>
                <p><strong>Number:</strong> ${selectedPayment.number}</p>
                <br><p>${selectedPayment.description}</p><br>
            `;

                    // Wallet হলে কোনো trx input লাগবে না
                    if (selectedPayment.method === "Wallet") {
                        trxBox.style.display = "none";
                        paymentNumberBox.style.display = "none";
                    } else {
                        trxBox.style.display = "block";
                    }
                });
            });

            checkoutBtn.addEventListener("click", () => {
                const pid = playerIdInput.value.trim();
                const trxId = trxIdInput.value.trim();
                const payNumber = paymentNumberInput.value.trim();
                let valid = true;

                if (!(pid.length >= 6 && pid.length <= 13)) {
                    document.getElementById("playerError").textContent = "Player ID must be 6-13 digits!";
                    valid = false;
                } else document.getElementById("playerError").textContent = "";

                if (!selectedPackage) {
                    document.getElementById("packageError").textContent = "অনুগ্রহ করে প্যাকেজ নির্বাচন করুন!";
                    valid = false;
                }

                if (!selectedPayment) {
                    showResponse("error", "❌ অনুগ্রহ করে পেমেন্ট মেথড নির্বাচন করুন!");
                    valid = false;
                }

                if (selectedPayment && selectedPayment.method !== "Wallet") {
                    if (trxId.length < 5) {
                        document.getElementById("trxError").textContent = "Valid Transaction ID দিন!";
                        valid = false;
                    } else document.getElementById("trxError").textContent = "";
                }

                if (!valid) return;

                const orderData = {
                    product_id: "{{ $product->id }}",
                    item_id: selectedPackage,
                    payment_id: selectedPayment.id,
                    customer_data: pid,
                    transaction_id: trxId,
                    payment_number: payNumber,
                    _token: "{{ csrf_token() }}"
                };

                checkoutBtn.disabled = true;
                showLoading();

                fetch("{{ route('addOrder') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify(orderData)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            showResponse("success", "✅ অর্ডার সফলভাবে সম্পন্ন হয়েছে!<br>Order ID: " + data.order.id);
                            setTimeout(() => window.location.href = "/thank-you", 2000);
                        } else {
                            showResponse("error", "❌ ব্যর্থ: " + data.message);
                            if (data.message.includes("Transaction ID and payment number")) {
                                trxBox.style.display = "block";
                                paymentNumberBox.style.display = "block";
                            }
                        }
                    })
                    .catch(() => {
                        showResponse("error", "⚠️ সার্ভার এরর, আবার চেষ্টা করুন।");
                    });
            });
        });
    </script>
@endpush
