@section('content')
    <style>
        .body{
            padding: 25px;
        }
        .payment-option.active {
            border: 2px solid green;
            background: #f0fff0;
        }
    </style>

    <!-- Step 3: Payment Selection -->
    <br>
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

    <script>
        let selectedMethod = null;

        // Method selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function () {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                selectedMethod = {
                    id: this.dataset.id,
                    number: this.dataset.number,
                    method: this.dataset.method,
                    description: this.dataset.description
                };

                // Show/hide input fields
                if (selectedMethod.method !== 'Wallet') {
                    document.getElementById('trxBox').style.display = 'block';
                    document.getElementById('paymentNumberBox').style.display = 'block';
                } else {
                    document.getElementById('trxBox').style.display = 'none';
                    document.getElementById('paymentNumberBox').style.display = 'none';
                }

                // Show details
                document.getElementById('paymentDetails').innerHTML = `
                <p><strong>Method:</strong> ${selectedMethod.method}</p>
                <p><strong>Number:</strong> ${selectedMethod.number}</p>
                <p><strong>Description:</strong> ${selectedMethod.description}</p>
            `;
            });
        });

        // Submit
        document.getElementById('checkoutBtn').addEventListener('click', function () {
            if (!selectedMethod) {
                alert('একটি পেমেন্ট পদ্ধতি নির্বাচন করুন');
                return;
            }

            const trxId = document.getElementById('trxId').value;
            const paymentNumber = document.getElementById('paymentNumber').value;

            const data = {
                method_id: selectedMethod.id,
                method: selectedMethod.method,
                trxId: trxId,
                paymentNumber: paymentNumber,
                _token: "{{ csrf_token() }}"
            };

            fetch("{{ url('/deposit') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(response => {
                    alert('Deposit request sent successfully!');
                    console.log(response);
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong!');
                });
        });
    </script>
@endsection
