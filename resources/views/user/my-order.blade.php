@extends('user.master')

@section('title', 'My Orders')

@section('content')
    <div class="container py-4">

        <!-- Page Title -->
        <div class="page-header mb-4 text-center">
            <h2 class="fw-bold">My Orders</h2>
            <p class="text-muted">Your recent orders and their statuses</p>
        </div>

        <!-- Orders Table -->
        <div class="order-history">
            <table class="order-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>{{ number_format($order->total, 2) }} ৳</td>
                        <td>
                        <span class="status {{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-view">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .order-history {
            overflow-x: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-table thead {
            background: #f8f9fa;
        }
        .order-table th, .order-table td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .order-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            color: #555;
        }
        .order-table td {
            color: #333;
        }
        .status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status.completed {
            background: #d4edda;
            color: #155724;
        }
        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .btn-view {
            display: inline-block;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-view:hover {
            background: #0056b3;
        }
    </style>
@endpush
