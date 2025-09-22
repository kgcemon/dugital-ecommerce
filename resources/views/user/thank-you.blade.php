@extends('user.master')

@section('title', "Order Placed")

@section('content')


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
        <div id="nickname-box" class="selection-panel completed" style="text-align:center; margin:20px 0; display:none;">
            <div id="nickname-card">
                <p>🎮 Player: <span id="nickname-text">--</span></p>
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
                    document.getElementById("loading-box").style.display = "none"; // hide loader
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
                    document.getElementById("loading-box").style.display = "none"; // hide loader
                    document.getElementById("nickname-text").textContent = "Error loading";
                    document.getElementById("nickname-box").style.display = "block";
                });
        });
    </script>

@endsection
