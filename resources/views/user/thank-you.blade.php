@extends('user.master')

@section('title', "Order Placed")

@section('content')

    <style>
        .summary-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 18px;
            gap: 15px;
        }
        .summary-row img {
            border-radius: 10px;
            max-width: 90px;
            max-height: 90px;
            object-fit: cover;
        }
        .summary-info p {
            margin: 4px 0;
            font-size: 15px;
            color: white;
        }
        .summary-info strong {
            color: white;
        }
        .selection-title h3 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 18px;
            color: white;
        }
        /* Nickname Card */
        #nickname-card {
            display:inline-block;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            padding: 15px 25px;
            border-radius: 15px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-align: center;
        }
        #nickname-card p {
            margin: 5px 0;
        }
    </style>

    <div class="container">
        <div class="selection-title">
            <h3>Thank You!</h3>
        </div>

        {{-- Product Row --}}
        <div class="summary-row selection-panel completed">
            <img src="/{{$order->product->image}}" alt="Product Image" height="80" width="80">
            <div class="summary-info">
                <p><strong>Order ID:</strong> {{$order->id}}</p>
                <p><strong>Items:</strong> {{$order->item->name ?? $order->product->name}}</p>
                <p><strong>Total:</strong> {{$order->total}}৳</p>
            </div>
        </div>

        {{-- Nickname Card (Dynamic) --}}
        <div id="nickname-box" class="selection-panel completed" style="text-align:center; margin:20px 0; display:none;">
            <div id="nickname-card">
                <p>🎮 Player: <span id="nickname-text">Loading...</span></p>
                <p>⭐ Level: <span id="level-text">--</span></p>
                <p>🏆 Rank: <span id="rank-text">--</span></p>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="summary-row selection-panel completed">
            <div class="summary-info">
                <p><strong>{{$order->product->input_name}}:</strong> {{$order->customer_data}}</p>
            </div>
        </div>

        {{-- Payment Row --}}
        <div class="summary-row selection-panel {{$order->status == 'delivered'  || $order->status == 'processing' ? 'completed' : 'pending' }}">
            <img src="{{$order->paymentMethod->icon}}" alt="{{$order->paymentMethod->method}}">
            <div class="summary-info">
                <p><strong>Method:</strong> {{$order->paymentMethod->method}}</p>
                @if($order->paymentMethod->method != 'Wallet')
                    <p><strong>Number:</strong> {{$order->transaction_id ?? ''}}</p>
                    <p><strong>TrxID:</strong> {{$order->number ?? ''}}</p>
                @endif
            </div>
        </div>

        {{-- Support Info --}}
        <div class="selection-panel">
            <h2>Support</h2>
            <div class="card-title"><p>
                    Call: 01828861788 <br>
                    facebook : <a href="https://m.me/Codzshop">Codzshop</a>
                </p></div>
        </div>
    </div>

    {{-- JS Fetch --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const uid = "{{$order->customer_data}}"; // dynamic uid
            const apiUrl = `https://ff-eight-eta.vercel.app/api/account?uid=${uid}&region=ru`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.basicInfo) {
                        document.getElementById("nickname-text").textContent = data.basicInfo.nickname || "Unknown";
                        document.getElementById("level-text").textContent = data.basicInfo.level || "--";
                        document.getElementById("rank-text").textContent = data.basicInfo.rank || "--";
                        document.getElementById("nickname-box").style.display = "block";
                    } else {
                        document.getElementById("nickname-text").textContent = "Not Found";
                        document.getElementById("nickname-box").style.display = "block";
                    }
                })
                .catch(error => {
                    console.error("Error fetching nickname:", error);
                    document.getElementById("nickname-text").textContent = "Error loading";
                    document.getElementById("nickname-box").style.display = "block";
                });
        });
    </script>

@endsection
