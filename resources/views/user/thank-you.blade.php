@extends('user.master')

@section('title', "Order Placed")

@section('content')
    <style>
        .container{max-width:780px;margin:24px auto;padding:0 16px;}
        .card{background:#fff;border:1px solid #edf2f7;border-radius:18px;box-shadow:0 10px 25px rgba(0,0,0,.06);overflow:hidden}
        .header{display:flex;align-items:center;gap:14px;padding:22px 22px 12px;border-bottom:1px solid #f1f5f9}
        .success-icon{width:46px;height:46px;flex:0 0 46px}
        .success-icon circle{stroke:#22c55e;stroke-width:2;fill:#dcfce7}
        .success-icon path{stroke:#16a34a;stroke-linecap:round;stroke-linejoin:round;stroke-width:3;fill:none}
        .title{margin:0;font-size:22px;font-weight:700;color:#0f172a}
        .sub{margin:2px 0 0;color:#475569;font-size:14px}
        .body{padding:18px 22px 22px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
        @media (max-width:640px){.grid{grid-template-columns:1fr}}
        .block{background:#f8fafc;border:1px solid #eef2f7;border-radius:14px;padding:14px}
        .block h4{margin:0 0 10px;font-size:14px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:.04em}
        .row{display:flex;align-items:center;gap:12px}
        .thumb{width:60px;height:60px;border-radius:12px;object-fit:cover;background:#e2e8f0;flex:0 0 60px;display:block}
        .logo{width:52px;height:52px;border-radius:12px;object-fit:contain;background:#fff;border:1px solid #e2e8f0;flex:0 0 52px;display:block}
        .kv{display:grid;grid-template-columns:140px 1fr;gap:8px 12px;margin-top:10px}
        .kv b{color:#334155}
        .kv span{color:#0f172a}
        .footer{padding:14px 22px 22px;color:#64748b;font-size:12px}
        .badge{display:inline-flex;align-items:center;gap:6px;background:#ecfeff;color:#0e7490;border:1px solid #a5f3fc;padding:6px 10px;border-radius:999px;font-weight:600;font-size:12px}
        .done-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.2)}
    </style>

    <div class="container">
        <div class="card">

            <!-- Header with DONE SVG -->
            <div class="header">
                <svg class="success-icon" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22"></circle>
                    <path d="M14 24l6 6 14-14"></path>
                </svg>
                <div>
                    <h3 class="title">Thank You! Your order is {{$order->status}}</h3>
                    <p class="sub">A receipt has been generated with the details below.</p>
                </div>
            </div>

            <div class="body">
                <div class="grid">
                    <!-- Product Block -->
                    <div class="block">
                        <h4>Product</h4>
                        <div class="row">
                            @php
                                $productImage = optional($order->item)->image ?? optional($order->product)->image ?? null;
                                $productName  = optional($order->item)->name  ?? optional($order->product)->name  ?? 'N/A';
                            @endphp

                            @if($productImage)
                                <img class="thumb" src="/{{$productImage}}" alt="Product image">
                            @else
                                <!-- Fallback placeholder -->
                                <div class="thumb" aria-hidden="true"></div>
                            @endif

                            <div style="min-width:0">
                                <div style="font-weight:700;color:#0f172a">{{ $productName }}</div>
                                <div class="kv" style="margin-top:8px">
                                    <b>Order ID</b><span>{{ $order->uid ?? $order->id }}</span>
                                    <b>Quantity</b><span>{{ $order->quantity ?? 1 }}</span>
                                    <b>Total Paid</b><span>{{ number_format($order->total, 2) }}৳</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Block -->
                    <div class="block">
                        <h4>Payment</h4>
                        <div class="row">
                            @php
                                $pm = optional($order->paymentMethod);
                                $pmLogo = $pm->icon ?? $pm->logo ?? null;
                                $pmName = $pm->method ?? $pm->name ?? 'Payment Method';
                            @endphp

                            @if($pmLogo)
                                <img class="logo" src="{{ Str::startsWith($pmLogo, ['http://','https://']) ? $pmLogo : asset('storage/'.$pmLogo) }}" alt="Payment method logo">
                            @else
                                <!-- Fallback placeholder -->
                                <div class="logo" aria-hidden="true"></div>
                            @endif

                            <div style="min-width:0">
                                <div style="font-weight:700;color:#0f172a">{{ $pmName }}</div>
                                <div class="kv" style="margin-top:8px">
                                    <b>Number</b><span>{{ $order->number ?? '—' }}</span>
                                    <b>TrxID</b><span>{{ $order->transaction_id ?? '—' }}</span>
                                    <b>Status</b><span class="badge"><span class="done-dot"></span> Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order summary (optional extra) -->
                <div class="block" style="margin-top:18px">
                    <h4>Summary</h4>
                    <div class="kv">
                        <b>Items</b><span>{{ $productName }}</span>
                        <b>Customer</b><span>{{ $order->customer_data }}</span>
                        <b>Paid</b><span>{{ number_format($order->total, 2) }}৳</span>
                    </div>
                </div>
            </div>

            <div class="footer">
                If you have any questions, reply to your order email and we’ll help right away.
            </div>
        </div>
    </div>
@endsection
