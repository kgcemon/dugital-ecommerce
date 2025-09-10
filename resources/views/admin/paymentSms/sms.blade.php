@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h4>Payment SMS</h4>
            <!-- Add SMS Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSmsModal">
                Add SMS
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
                    @forelse($data as $sms)
                        <tr>
                            <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                            <td>{{ $sms->amount }}</td>
                            <td>{{ $sms->sender }}</td>
                            <td>{{ $sms->number }}</td>
                            <td>{{ $sms->trxID }}</td>
                            <td>
                                @php
                                    $statusText = $sms->status == 0 ? 'Pending' : ($sms->status == 1 ? 'Completed' : 'Failed');
                                    $statusClass = $sms->status == 0 ? 'bg-warning text-dark' : ($sms->status == 1 ? 'bg-success' : 'bg-danger');
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>{{ $sms->created_at ? $sms->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td>
                                <!-- Edit Status Button -->
                                <button class="btn btn-sm btn-primary edit-btn"
                                        data-id="{{ $sms->id }}"
                                        data-status="{{ $sms->status }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStatusModal">
                                    Edit
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.sms.delete', $sms->id) }}" method="POST" class="d-inline-block">
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
                            <td colspan="8" class="text-muted">No SMS found.</td>
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

    <!-- Add SMS Modal -->
    <div class="modal fade" id="addSmsModal" tabindex="-1" aria-labelledby="addSmsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addSmsLabel">Add SMS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sms.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="text" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sender</label>
                            <input type="text" name="sender" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number</label>
                            <input type="text" name="number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" name="trxID" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="0">Pending</option>
                                <option value="1">Completed</option>
                                <option value="2">Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add SMS</button>
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
                <form action="{{ route('admin.sms.update-status') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="sms_id" id="editSmsId">
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
            const editSmsId = document.getElementById("editSmsId");
            const editStatusSelect = document.getElementById("editStatusSelect");

            editButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    editSmsId.value = this.dataset.id;
                    editStatusSelect.value = this.dataset.status;
                });
            });
        });
    </script>
@endpush
