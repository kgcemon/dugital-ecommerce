@extends('user.master')

@section('title', "Profile")

@section('content')

<div style="max-width: 400px; margin: 20px auto; padding: 20px; border-radius: 15px; background:linear-gradient(10deg,#0F0C29 0%,#302B63 50%,#24243e 100%); color: #fff; font-family: Arial, sans-serif; rgba(0,0,0,0.3);">
    <!-- Profile Header -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiU0_6Mf8AnR9ny0woh2-u7LcoB2oWrks8OpSQfhzA9xxfk9CL4oxNQnWjoxwkDJwwUnY&usqp=CAU"
             alt="Profile Picture"
             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #28a745;">
        <div>
            <div style="font-weight: 600; font-size: 18px;">{{ Auth::user()->name ?? 'User Name' }}</div>
            <div style="font-size: 14px; color: #ccc;">{{ Auth::user()->email ?? 'user@example.com' }}</div>
        </div>
    </div>


    <!-- Summary Stats -->
    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
        <div style="flex: 1 1 45%; background-color: #2b2b3f; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Total Orders</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $totalOrders ?? 0 }}</div>
        </div>
        <div style="flex: 1 1 45%; background-color: #2b2b3f; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Completed Orders</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $completedOrders ?? 0 }}</div>
        </div>
        <div style="flex: 1 1 45%; background-color: #2b2b3f; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Pending Orders</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $pendingOrders ?? 0 }}</div>
        </div>
        <div style="flex: 1 1 45%; background-color: #2b2b3f; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Referral Income</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $refIncome ?? 0 }}৳</div>
        </div>
        <div style="flex: 1 1 100%; background-color: #2b2b3f; padding: 10px; border-radius: 10px; text-align: center;">
            <div style="font-size: 14px; color: #ccc;">Total Referrals</div>
            <div style="font-weight: 600; font-size: 16px;">{{ $totalReferrals ?? 0 }}</div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div>
        <div style="font-weight: 600; font-size: 16px; margin-bottom: 10px;">Recent Transactions</div>
        <div style="max-height: 200px; overflow-y: auto;">
            @foreach($recentTransactions as $txn)
                <div style="display: flex; justify-content: space-between; padding: 8px 10px; background-color: #2b2b3f; margin-bottom: 5px; border-radius: 8px;">
                    <span>{{ $txn->type ?? 'Order' }}</span>
                    <span>{{ $txn->amount ?? 0 }}৳</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
