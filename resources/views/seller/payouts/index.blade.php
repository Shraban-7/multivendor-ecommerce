@extends('seller.layouts.app')
@section('title', 'Payouts')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Payouts</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.payouts.methods') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1">
                <i data-feather="credit-card" style="width: 16px; height: 16px;"></i> Payment Methods
            </a>
            <a href="{{ route('seller.payouts.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i> Request Payout
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-bg-success me-3">
                            <i data-feather="dollar-sign" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Available Balance</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ money($availableBalance) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-bg-warning me-3">
                            <i data-feather="clock" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Pending Clearance</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ money($pendingBalance) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-bg-primary me-3">
                            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Total Withdrawn</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ money($totalWithdrawn) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-semibold text-dark mb-0">Payout History</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm w-auto" id="statusFilter" onchange="window.location.href='{{ route('seller.payouts.index') }}?status='+this.value">
                        <option value="">All Status</option>
                        <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Processing</option>
                        <option value="2" {{ $statusFilter === '2' ? 'selected' : '' }}>Completed</option>
                        <option value="3" {{ $statusFilter === '3' ? 'selected' : '' }}>Cancelled</option>
                        <option value="4" {{ $statusFilter === '4' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Charge</th>
                            <th class="py-3">Net Amount</th>
                            <th class="py-3">Method</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Date</th>
                            <th class="py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                            <tr>
                                <td class="px-4">{{ $payout->id }}</td>
                                <td class="fw-semibold">{{ money($payout->amount) }}</td>
                                <td class="text-muted">{{ money($payout->charge) }}</td>
                                <td class="fw-semibold">{{ money($payout->net_amount) }}</td>
                                <td>
                                    @if ($payout->payoutMethod)
                                        <span class="badge-soft-info">{{ $payout->payoutMethod->methodLabel() }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span>
                                </td>
                                <td class="text-muted small">{{ $payout->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('seller.payouts.show', $payout) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="eye" class="icon-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i data-feather="credit-card" style="width: 48px; height: 48px;" class="mb-3"></i>
                                    <p class="mb-0">No payout requests yet.</p>
                                    <a href="{{ route('seller.payouts.create') }}" class="btn btn-primary mt-2">Request Your First Payout</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($payouts->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-end">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
