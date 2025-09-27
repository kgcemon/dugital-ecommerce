@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">💰 Wallet Transactions</h5>
                <span class="badge bg-success">Balance: {{ number_format($balance, 2) }} ৳</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 text-center align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $key => $txn)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $txn->created_at->format('d M Y h:i A') }}</td>
                            <td>
                                @if($txn->type === 'credit')
                                    <span class="badge bg-success">Credit</span>
                                @else
                                    <span class="badge bg-danger">Debit</span>
                                @endif
                            </td>
                            <td>
                                @if($txn->type === 'credit')
                                    <span class="text-success fw-bold">+ {{ number_format($txn->amount, 2) }} ৳</span>
                                @else
                                    <span class="text-danger fw-bold">- {{ number_format($txn->amount, 2) }} ৳</span>
                                @endif
                            </td>
                            <td>
                                @if($txn->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($txn->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($txn->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $txn->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">No transactions found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
