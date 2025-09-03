@extends('user.master')

@section('title', "Deposit")

@section('content')
    <style>
        .body {
            margin: 15px;
        }
        .payment-option.selected {
            border: 2px solid #007bff;
            background: #f0f8ff;
        }
    </style>

    <!-- Payment Selection -->
    <br>


    <div class="card body">
        <img src="wallet.png" alt="wallet" style="height:25px; margin-right:5px;"> {{$amount}}
    </div>
    <br>
    <div class="card body">
        @if (session('error'))
            {{ session('error') }}
        @endif
    </div>
    <br>
    <div class="selection-panel body" id="step3">
        <h2 class="selection-title">পেমেন্ট পদ্ধতি নির্বাচন করুন</h2>
        <div class="payment-methods" style="display:flex; flex-wrap:wrap; gap:10px;">
            @foreach($payment as $method)
                <div class="payment-option
                     @if($loop->first) selected @endif"
                     style="flex:1 1 calc(33.333% - 10px); padding:10px; border:1px solid #ccc; border-radius:8px; cursor:pointer;"
                     data-id="{{ $method->id }}"
                     data-number="{{ $method->number }}"
                     data-method="{{ $method->method }}"
                     data-description="{{ $method->description }}">
                    <img src="{{ $method->icon }}" alt="{{ $method->method }}" style="height:25px; margin-right:5px;">
                    {{ $method->method }}
                </div>
            @endforeach
        </div>

        <div class="payment-details" id="paymentDetails"></div>

        <!-- Transaction ID input -->
        <div id="trxBox" class="player-id-box" style="display:none;">
            <h2 class="selection-title">Transaction ID লিখুন</h2>
            <input type="text" id="trxId" placeholder="Enter Transaction ID">
        </div>

        <!-- Payment Number input (শুধু status==false এ show হবে) -->
        <div id="paymentNumberBox" class="player-id-box" style="display:none;">
            <h2 class="selection-title">Payment Number লিখুন</h2>
            <input type="text" id="paymentNumber" placeholder="Enter Payment Number">
        </div>
        <br>
        <!-- Submit Button -->
        <button class="checkout-btn" id="checkoutBtn">Submit Order</button>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let selectedPayment = null;

            const paymentOptions = document.querySelectorAll(".payment-option");
            const trxBox = document.getElementById("trxBox");
            const trxIdInput = document.getElementById("trxId");
            const paymentNumberBox = document.getElementById("paymentNumberBox");
            const paymentNumberInput = document.getElementById("paymentNumber");
            const checkoutBtn = document.getElementById("checkoutBtn");

            // ফাংশন: পেমেন্ট সিলেক্ট হলে UI সেট করব
            function selectPayment(el) {
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

                if (selectedPayment.method === "Wallet") {
                    trxBox.style.display = "none";
                    paymentNumberBox.style.display = "none";
                } else {
                    trxBox.style.display = "block";
                    paymentNumberBox.style.display = "none"; // ডিফল্টে hide
                }
            }

            // Auto select first payment method
            if (paymentOptions.length > 0) {
                selectPayment(paymentOptions[0]);
            }

            // যখন ইউজার অন্য অপশন ক্লিক করবে
            paymentOptions.forEach(el => {
                el.addEventListener("click", () => selectPayment(el));
            });

            // Submit
            checkoutBtn.addEventListener("click", () => {
                if (!selectedPayment) {
                    alert("অনুগ্রহ করে একটি পেমেন্ট মেথড নির্বাচন করুন!");
                    return;
                }

                const trxId = trxIdInput.value.trim();
                const payNumber = paymentNumberInput.value.trim();

                if (selectedPayment.method !== "Wallet") {
                    if (trxId.length < 5) {
                        alert("Valid Transaction ID দিন!");
                        trxIdInput.focus();
                        return;
                    }
                }

                // POST request
                fetch("{{ url('/deposit') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        payment_id: selectedPayment.id,
                        transaction_id: trxId,
                        payment_number: payNumber
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            alert("✅ ডিপোজিট সফল হয়েছে!");
                            window.location.href = "/thank-you";
                        } else {
                            // যদি ব্যর্থ হয় (status == false) তখন Payment Number চাইবে
                            paymentNumberBox.style.display = "block";
                            alert("❌ ব্যর্থ: " + data.message + " | অনুগ্রহ করে Payment Number দিন");
                        }
                    })
                    .catch(() => alert("⚠️ সার্ভার এরর, আবার চেষ্টা করুন।"));
            });
        });
    </script>
@endpush
