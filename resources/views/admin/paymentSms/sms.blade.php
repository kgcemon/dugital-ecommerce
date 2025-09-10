@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h4>Orders</h4>
            <!-- Add Order Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                Add Order
            </button>
        </div>

        <div class="card shadow-sm border-0">
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
                                <!-- Edit Status Button -->
                                <button class="btn btn-sm btn-primary edit-btn"
                                        data-id="{{ $order->id }}"
                                        data-status="{{ $order->status }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStatusModal">
                                    Edit
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
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

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addOrderLabel">Add Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.orders.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-control" required>
                                @foreach(App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item</label>
                            <select name="item_id" class="form-control" required>
                                @foreach(App\Models\Item::all() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer Data</label>
                            <input type="text" name="customer_data" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_id" class="form-control" required>
                                @foreach(App\Models\PaymentMethod::all() as $payment)
                                    <option value="{{ $payment->id }}">{{ $payment->method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Number</label>
                            <input type="text" name="payment_number" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Order</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editStatusLabel">Edit Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.orders.update-status') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" id="editOrderId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="editStatusSelect" class="form-control" required>
                                <option value="0">Pending</option>
                                <option value="1">Completed</option>
                                <option value="2">Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-btn");
            const editOrderId = document.getElementById("editOrderId");
            const editStatusSelect = document.getElementById("editStatusSelect");

            editButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    editOrderId.value = this.dataset.id;
                    editStatusSelect.value = this.dataset.status;
                });
            });
        });
    </script>
@endpush
