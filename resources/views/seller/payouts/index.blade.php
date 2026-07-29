@extends('seller.layouts.app')
@section('title', 'Payouts')

@section('content')
<div class="w-full px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Payouts</h4>
        <div class="flex gap-2">
            <a href="{{ route('seller.payouts.methods.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-transparent text-brand-deep text-sm font-medium border border-brand rounded-xs hover:bg-brand-tint focus:outline-none transition-colors gap-1">
                <i data-feather="credit-card" style="width: 16px; height: 16px;"></i> Payment Methods
            </a>
            <a href="{{ route('seller.payouts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i> Request Payout
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="icon-bg-success me-3">
                            <i data-feather="dollar-sign" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-ink-tertiary mb-0 text-sm">Available Balance</p>
                            <h3 class="font-bold mb-0 text-ink">{{ money($availableBalance) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="icon-bg-info me-3">
                            <i data-feather="trending-up" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-ink-tertiary mb-0 text-sm">Pending Earnings</p>
                            <h3 class="font-bold mb-0 text-ink">{{ money($pendingEarnings) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="icon-bg-warning me-3">
                            <i data-feather="clock" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-ink-tertiary mb-0 text-sm">Pending Clearance</p>
                            <h3 class="font-bold mb-0 text-ink">{{ money($pendingBalance) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="icon-bg-primary me-3">
                            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <p class="text-ink-tertiary mb-0 text-sm">Total Withdrawn</p>
                            <h3 class="font-bold mb-0 text-ink">{{ money($totalWithdrawn) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
            <div class="flex flex-wrap justify-between items-center gap-3">
                <h5 class="font-semibold text-ink mb-0">Payout History</h5>
                <div class="flex gap-2">
                    <select class="w-auto px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="statusFilter" onchange="window.location.href='{{ route('seller.payouts.index') }}?status='+this.value">
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
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Charge</th>
                            <th class="py-3">Net Amount</th>
                            <th class="py-3">Method</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Date</th>
                            <th class="py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                            <tr>
                                <td class="px-4">{{ $payout->id }}</td>
                                <td class="font-semibold">{{ money($payout->amount) }}</td>
                                <td class="text-ink-tertiary">{{ money($payout->charge) }}</td>
                                <td class="font-semibold">{{ money($payout->net_amount) }}</td>
                                <td>
                                    @if ($payout->payoutMethod)
                                        <span class="badge-soft-info">{{ $payout->payoutMethod->methodLabel() }}</span>
                                    @else
                                        <span class="text-ink-tertiary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span>
                                </td>
                                <td class="text-ink-tertiary text-sm">{{ $payout->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('seller.payouts.show', $payout) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">
                                        <i data-feather="eye" class="icon-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-ink-tertiary">
                                    <i data-feather="credit-card" style="width: 48px; height: 48px;" class="mb-3"></i>
                                    <p class="mb-0">No payout requests yet.</p>
                                    <a href="{{ route('seller.payouts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1 mt-2">Request Your First Payout</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($payouts->hasPages())
            <div class="px-5 py-3 border-t border-border bg-surface-muted flex justify-end">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection