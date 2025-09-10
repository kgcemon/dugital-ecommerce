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
                            <td>
                                @php
                                    $statusText = $order->status == 0 ? 'Pending' : ($order->status == 1 ? 'Completed' : 'Failed');
                                    $statusClass = $order->status == 0 ? 'bg-warning text-dark' : ($order->status == 1 ? 'bg-success' : 'bg-danger');
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary view-btn"
                                        data-amount="{{ $order->amount }}"
                                        data-sender="{{ $order->sender }}"
                                        data-number="{{ $order->number }}"
                                        data-trx="{{ $order->trxID }}"
                                        data-order="{{ $order->order_number }}"
                                        data-status="{{ $statusText }}"
                                        data-created="{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}">
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
                <div class="modal-body" id="orderDetails">
                    <!-- Dynamic content will be injected here -->
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
                    const amount = this.dataset.amount;
                    const sender = this.dataset.sender;
                    const number = this.dataset.number;
                    const trx = this.dataset.trx;
                    const status = this.dataset.status;
                    const order = this.dataset.order;
                    const created = this.dataset.created;

                    orderDetails.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-bordered text-start">
                        <tr><th>Amount</th><td>${amount}</td></tr>
                        <tr><th>Sender</th><td>${sender}</td></tr>
                        <tr><th>Number</th><td>${number}</td></tr>
                        <tr><th>Transaction ID</th><td>${trx}</td></tr>
                        <tr><th>Order</th><td>${order}</td></tr>
                        <tr><th>Status</th><td>${status}</td></tr>
                        <tr><th>Created At</th><td>${created}</td></tr>
                    </table>
                </div>
            `;

                    modal.show();
                });
            });
        });
    </script>
@endpush
