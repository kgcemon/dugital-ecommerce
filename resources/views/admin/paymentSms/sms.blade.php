@extends('admin.layouts.app')

<style>
    .hover-row:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.2s ease-in-out;
    }
    .table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 14px;
        text-transform: uppercase;
    }
    .btn {
        transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .form-select, .form-control {
        border-radius: 8px;
    }
</style>

@section('content')
    <div class="container mt-4">

        <!-- Header & Filters -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 px-3 py-2 bg-light rounded shadow-sm">
            <h4 class="mb-2 mb-md-0 fw-bold">📩 Payment SMS</h4>

            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select shadow-sm">
                    <option value="">All Status</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                </select>

                <input type="text" id="searchInput"
                       class="form-control shadow-sm"
                       placeholder="🔍 Search by sender, number, trxID, or amount"
                       value="{{ request('search') }}">
            </div>
        </div>

        <!-- Table Container -->
        <div id="smsTableContainer">
            @include('admin.paymentSms.sms_table', ['data' => $data])
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const smsTableContainer = document.getElementById('smsTableContainer');

            let debounceTimer;

            // Loader
            function showLoader() {
                smsTableContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 fw-semibold">Loading...</p>
            </div>`;
            }

            // Fetch Data
            function fetchData() {
                const status = statusFilter.value;
                const search = searchInput.value;

                showLoader();

                fetch(`{{ route('admin.sms') }}?status=${status}&search=${search}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(response => response.text())
                    .then(html => {
                        smsTableContainer.innerHTML = html;
                    })
                    .catch(error => {
                        smsTableContainer.innerHTML = `
                <div class="alert alert-danger text-center">
                    <strong>Error:</strong> Failed to load data.
                </div>`;
                        console.error(error);
                    });
            }

            // Events
            statusFilter.addEventListener('change', fetchData);
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchData, 400);
            });
        });
    </script>
@endpush
