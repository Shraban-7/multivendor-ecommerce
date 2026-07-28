@extends('admin.layouts.app')
@section('title', 'Seller Payouts')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Seller Payouts</h3>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-bg-warning me-3">
                        <i data-feather="clock" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Pending</p>
                        <h4 class="fw-bold mb-0">{{ money($stats['pending']) }}</h4>
                        <small class="text-muted">{{ $stats['total_pending_count'] }} requests</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-bg-info me-3">
                        <i data-feather="loader" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Processing</p>
                        <h4 class="fw-bold mb-0">{{ money($stats['processing']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-bg-success me-3">
                        <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Completed</p>
                        <h4 class="fw-bold mb-0">{{ money($stats['completed']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-bg-primary me-3">
                        <i data-feather="dollar-sign" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Total Paid Out</p>
                        <h4 class="fw-bold mb-0">{{ money($stats['completed']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Processing</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Completed</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Cancelled</option>
                    <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="seller_id" class="form-control form-control-sm" placeholder="Seller ID" value="{{ request('seller_id') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.payouts.index') }}" class="btn btn-sm btn-light border">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3">Seller</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Charge</th>
                        <th class="py-3">Net</th>
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $payout->seller->businessAvatar }}" height="32" width="32" class="rounded me-2 border" style="object-fit:scale-down;">
                                    <div>
                                        <span class="fw-semibold small">{{ $payout->seller->business_name ?? $payout->seller->name }}</span>
                                        <small class="d-block text-muted">ID: {{ $payout->seller_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">{{ money($payout->amount) }}</td>
                            <td class="text-muted">{{ money($payout->charge) }}</td>
                            <td class="fw-semibold">{{ money($payout->net_amount) }}</td>
                            <td>
                                @if ($payout->payoutMethod)
                                    <span class="badge-soft-info small">{{ $payout->payoutMethod->methodLabel() }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span>
                            </td>
                            <td class="small text-muted">{{ $payout->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-sm btn-primary">
                                    <i data-feather="eye" class="icon-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i data-feather="credit-card" style="width: 48px; height: 48px;" class="mb-3"></i>
                                <p class="mb-0">No payouts found.</p>
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
@endsection
