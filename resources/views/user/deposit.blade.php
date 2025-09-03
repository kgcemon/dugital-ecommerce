@extends('user.master')

@section('title', "Deposit")

@section('content')
    <style>
        .body{
            padding: 25px;
        }
        .payment-option.selected {
            border: 2px solid #007bff;
            background: #f0f8ff;
        }
    </style>

    <!-- Payment Selection -->
    <br>
    <div class="selection-panel body" id="step3">
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
                            <span style="font-weight:600; color:#28a745;">
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

                    if (selectedPayment.method === "Wallet") {
                        trxBox.style.display = "none";
                        paymentNumberBox.style.display = "none";
                    } else {
                        trxBox.style.display = "block";
                        paymentNumberBox.style.display = "block";
                    }
                });
            });

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
                    if (payNumber.length < 5) {
                        alert("Valid Payment Number দিন!");
                        paymentNumberInput.focus();
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
                            alert("❌ ব্যর্থ: " + data.message);
                        }
                    })
                    .catch(() => alert("⚠️ সার্ভার এরর, আবার চেষ্টা করুন।"));
            });
        });
    </script>
@endpush
