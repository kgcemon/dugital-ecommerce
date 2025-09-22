@extends('user.master')

@section('title', "Order Placed")

@section('content')

    <style>
        .summary-row {
            display: flex!important;
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
            color: #ddd;
        }
        .summary-info strong {
            color: #fff;
        }
        .selection-title h3 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 18px;
            color: #fff;
        }
        /* Loader */
        .loader {
            border: 4px solid #333;
            border-top: 4px solid #4e54c8;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        {{-- Loading Spinner --}}
        <div id="loading-box" style="text-align:center; margin:20px 0;">
            <div class="loader"></div>
            <p style="color:#aaa;">Fetching player info...</p>
        </div>

        {{-- Nickname Card (Dynamic) --}}
        <div id="nickname-box" class="summary-row selection-panel completed" style="display:none;">
            <img id="avatar-img" src="" height="60" width="60" alt="Player Avatar">
            <div class="summary-info" id="nickname-card">
                <p><strong>🎮 Player:</strong> <span id="nickname-text">--</span></p>
                <p><strong>⭐ Level:</strong> <span id="level-text">--</span> <strong>🏆 Rank: </strong> <span id="rank-text">--</span></p>
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
            const apiUrl = `/api/player-info/${uid}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("loading-box").style.display = "none"; // hide loader

                    if (data && data.basicInfo) {
                        // nickname, level, rank
                        document.getElementById("nickname-text").textContent = data.basicInfo.nickname || "Unknown";
                        document.getElementById("level-text").textContent = data.basicInfo.level || "--";
                        document.getElementById("rank-text").textContent = data.basicInfo.rank || "--";

                        // avatar image dynamic
                        if (data.profileInfo && data.profileInfo.avatarId) {
                            const avatarId = data.profileInfo.avatarId;
                            const avatarUrl = `https://raw.githubusercontent.com/ashqking/FF-Items/main/ICONS/${avatarId}.png`;
                            document.getElementById("avatar-img").src = avatarUrl;
                        }

                        document.getElementById("nickname-box").style.display = "flex";
                    } else {
                        document.getElementById("nickname-text").textContent = "Not Found";
                        document.getElementById("nickname-box").style.display = "flex";
                    }
                })
                .catch(error => {
                    console.error("Error fetching nickname:", error);
                    document.getElementById("loading-box").style.display = "none"; // hide loader
                    document.getElementById("nickname-text").textContent = "Error loading";
                    document.getElementById("nickname-box").style.display = "flex";
                });
        });
    </script>

@endsection
