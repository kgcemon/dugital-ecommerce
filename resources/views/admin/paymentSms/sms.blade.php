@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <h4>Payment SMS</h4>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Status Filter -->
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Completed</option>
                    <option value="2">Failed</option>
                </select>
                <!-- Search -->
                <input type="text" id="searchInput" class="form-control" placeholder="Search by sender, number, trxID, or amount">
                <!-- Add SMS Button -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSmsModal">
                    Add SMS
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered align-middle text-center" id="smsTable">
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
                        <tr data-status="{{ $sms->status }}">
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
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-primary edit-btn"
                                            data-id="{{ $sms->id }}"
                                            data-status="{{ $sms->status }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStatusModal">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.sms.delete', $sms->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
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
                    {{ $data->links('admin.layouts.partials.__pagination') }}
                </div>
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

            // Edit button functionality
            editButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    editSmsId.value = this.dataset.id;
                    editStatusSelect.value = this.dataset.status;
                });
            });

            // Filter & Search
            const statusFilter = document.getElementById("statusFilter");
            const searchInput = document.getElementById("searchInput");
            const table = document.getElementById("smsTable");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            // Create "No results found" row
            let noResultsRow = document.createElement("tr");
            noResultsRow.innerHTML = `<td colspan="8" class="text-muted text-center">No results found.</td>`;
            noResultsRow.style.display = "none";
            tbody.appendChild(noResultsRow);

            function filterTable() {
                const statusVal = statusFilter.value;
                const searchVal = searchInput.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowStatus = row.dataset.status;
                    const text = row.textContent.toLowerCase();
                    const matchesStatus = statusVal === "" || rowStatus === statusVal;
                    const matchesSearch = text.includes(searchVal);
                    const showRow = matchesStatus && matchesSearch;

                    row.style.display = showRow ? "" : "none";
                    if (showRow) visibleCount++;
                });

                // Show "No results" if nothing matches
                noResultsRow.style.display = visibleCount === 0 ? "" : "none";
            }

            statusFilter.addEventListener("change", filterTable);
            searchInput.addEventListener("input", filterTable);
        });
    </script>
@endpush

