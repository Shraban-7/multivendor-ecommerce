@extends('admin.layouts.app')
@section('title', 'Seller Payouts')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-3">
    <h3 class="font-bold mb-0">Seller Payouts</h3>
</div>

<div class="grid grid-cols-1 gap-3 mb-4">
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="icon-bg-feedback-warning me-3">
                        <i data-feather="clock" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-ink-tertiary mb-0 text-sm">Pending</p>
                        <h4 class="font-bold mb-0">{{ money($stats['pending']) }}</h4>
                        <small class="text-ink-tertiary">{{ $stats['total_pending_count'] }} requests</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="icon-bg-feedback-info me-3">
                        <i data-feather="loader" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-ink-tertiary mb-0 text-sm">Processing</p>
                        <h4 class="font-bold mb-0">{{ money($stats['processing']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="icon-bg-feedback-success me-3">
                        <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-ink-tertiary mb-0 text-sm">Completed</p>
                        <h4 class="font-bold mb-0">{{ money($stats['completed']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="icon-bg-brand-deep me-3">
                        <i data-feather="dollar-sign" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-ink-tertiary mb-0 text-sm">Total Paid Out</p>
                        <h4 class="font-bold mb-0">{{ money($stats['completed']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b py-3">
        <form method="GET" class="grid grid-cols-1 gap-2 items-end">
            <div class="col-auto">
                <select name="status" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Processing</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Completed</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Cancelled</option>
                    <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="seller_id" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Seller ID" value="{{ request('seller_id') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_from" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" value="{{ request('date_from') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_to" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" value="{{ request('date_to') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.payouts.index') }}" class="btn btn-light btn-sm">Reset</a>
            </div>
        </form>
    </div>
    <div class="p-5 p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3">Seller</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Charge</th>
                        <th class="py-3">Net</th>
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
                            <td>
                                <div class="flex items-center">
                                    <img src="{{ $payout->seller->businessAvatar }}" height="32" width="32" class="rounded me-2 border" style="object-fit:scale-down;">
                                    <div>
                                        <span class="font-semibold text-sm">{{ $payout->seller->business_name ?? $payout->seller->name }}</span>
                                        <small class="block text-ink-tertiary">ID: {{ $payout->seller_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold">{{ money($payout->amount) }}</td>
                            <td class="text-ink-tertiary">{{ money($payout->charge) }}</td>
                            <td class="font-semibold">{{ money($payout->net_amount) }}</td>
                            <td>
                                @if ($payout->payoutMethod)
                                    <span class="badge-soft-info text-sm">{{ $payout->payoutMethod->methodLabel() }}</span>
                                @else
                                    <span class="text-ink-tertiary text-sm">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span>
                            </td>
                            <td class="text-sm text-ink-tertiary">{{ $payout->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-primary btn-sm">
                                    <i data-feather="eye" class="icon-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-ink-tertiary">
                                <i data-feather="credit-bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="width: 48px; height: 48px;" class="mb-3"></i>
                                <p class="mb-0">No payouts found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($payouts->hasPages())
        <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t flex justify-end">
            {{ $payouts->links() }}
        </div>
    @endif
</div>
@endsection
