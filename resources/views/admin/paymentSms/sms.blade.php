@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">Payment SMS</h5>
                <form action="{{ route('admin.sms-search') }}" method="GET" class="d-flex" style="gap: 8px;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="Search by TrxID or Number">
                    <button type="submit" class="btn btn-sm btn-light">Search</button>
                </form>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Sender</th>
                        <th>Number</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $order)
                        <tr>
                            <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                            <td>{{ $order->amount }}</td>
                            <td>{{ $order->sender }}</td>
                            <td>{{ $order->number }}</td>
                            <td>{{ $order->trxID }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                @if($order->status == 0)
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary view-btn"
                                        data-id="{{ $order->id }}">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">No orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $data->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetails" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buttons = document.querySelectorAll(".view-btn");
            const modal = new bootstrap.Modal(document.getElementById('orderModal'));
            const orderDetails = document.getElementById("orderDetails");

            buttons.forEach(btn => {
                btn.addEventListener("click", function () {
                    let orderId = this.dataset.id;

                    orderDetails.innerHTML = `
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;

                    modal.show();

                    fetch(`/admin/orders/${orderId}`)
                        .then(res => res.json())
                        .then(data => {
                            orderDetails.innerHTML = `
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr><th>Amount</th><td>${data.amount}</td></tr>
                                    <tr><th>Sender</th><td>${data.sender}</td></tr>
                                    <tr><th>Number</th><td>${data.number}</td></tr>
                                    <tr><th>Transaction ID</th><td>${data.trxID}</td></tr>
                                    <tr><th>Status</th><td>${data.status_text}</td></tr>
                                    <tr><th>Status</th><td>${data.order_number}</td></tr>
                                    <tr><th>Created At</th><td>${data.created_at}</td></tr>
                                </table>
                            </div>
                        `;
                        })
                        .catch(err => {
                            orderDetails.innerHTML = `<div class="alert alert-danger">Failed to load order details.</div>`;
                        });
                });
            });
        });
    </script>
@endpush
