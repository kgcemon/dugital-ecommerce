@extends('user.master')

@section('title', "Profile")

@section('content')

<div style="max-width: 400px; margin: 20px auto; padding: 20px; border-radius: 15px; background:linear-gradient(10deg,#0F0C29 0%,#302B63 50%,#24243e 100%); color: #fff; font-family: Arial, sans-serif; rgba(0,0,0,0.3);">
    <!-- Profile Header -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <img src="{{ Auth::user()->image}}"
             alt="Profile Picture"
             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #28a745;">
        <div>
            <div style="font-weight: 600; font-size: 18px;">{{ Auth::user()->name ?? 'User Name' }}</div>
            <div style="font-size: 14px; color: #ccc;">{{ Auth::user()->email ?? 'user@example.com' }}</div>
        </div>
    </div>


    <!-- Summary Stats -->
    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px;">
        <div class="panelData" style="flex: 1 1 45%; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Total Orders</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $totalOrders ?? 0 }}</div>
        </div>
        <div class="panelData" style="flex: 1 1 45%;  padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Completed Orders</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $completedOrders ?? 0 }}</div>
        </div>
        <div class="panelData" style="flex: 1 1 45%; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Referral Income</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $refIncome ?? 0 }}৳</div>
        </div>
        <div class="panelData" style="flex: 1 1 100%; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Total Referrals</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $totalReferrals ?? 0 }}</div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div>
        <div style="font-weight: 600; font-size: 16px; margin-bottom: 10px;">Recent Transactions</div>
        <div style="max-height: 200px; overflow-y: auto;">
            @foreach($recentTransactions as $txn)
                <div class="panelData" style="display: flex; justify-content: space-between; padding: 8px 10px; margin-bottom: 5px; border-radius: 8px;">
                    <span>{{ '#'. $txn->id}}</span>
                    <span>{{ $txn->status }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
