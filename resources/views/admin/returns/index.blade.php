@extends('admin.layouts.app')
@section('title', 'Return Management')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">Return Management</h4>
            <small class="text-ink-tertiary">Manage all customer return & exchange requests</small>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-4">
        @php
            $summaryCards = [
                ['key' => 'total',             'label' => 'Total',            'color' => '#374151'],
                ['key' => 'pending',           'label' => 'Pending',          'color' => '#d97706'],
                ['key' => 'awaiting_shipment', 'label' => 'Awaiting',         'color' => '#2563eb'],
                ['key' => 'approved',          'label' => 'Approved',         'color' => '#059669'],
                ['key' => 'rejected',          'label' => 'Rejected',         'color' => '#dc2626'],
                ['key' => 'refunded',          'label' => 'Refunded',         'color' => '#7c3aed'],
                ['key' => 'disputed',          'label' => 'Disputed',         'color' => '#dc2626'],
            ];
        @endphp
        @foreach ($summaryCards as $card)
            <div class="bg-white border border-border rounded-sm shadow-sm p-3">
                <span class="text-ink-tertiary text-sm">{{ $card['label'] }}</span>
                <h5 class="font-bold mb-0" style="color: {{ $card['color'] }}">{{ $counts[$card['key']] ?? 0 }}</h5>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-ink-secondary">Search & Filter</span>
                <a href="{{ route('admin.returns.index') }}" class="btn btn-sm {{ ! request('status') && ! request('disputed') ? 'btn-dark' : 'btn-light' }}">All</a>
                <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-light' }}">Pending</a>
                <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-light' }}">Approved</a>
                <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light' }}">Rejected</a>
                <a href="{{ route('admin.returns.index', ['status' => 'refunded']) }}" class="btn btn-sm {{ request('status') === 'refunded' ? 'btn-info' : 'btn-light' }}">Refunded</a>
                <a href="{{ route('admin.returns.index', ['disputed' => 1]) }}" class="btn btn-sm {{ request('disputed') ? 'btn-danger' : 'btn-light' }}">Disputed</a>

                <form method="GET" class="ms-auto flex gap-2">
                    <input type="text" name="search" class="w-56 px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Search RMA, order, customer..." value="{{ request('search') }}">
                    <button class="btn btn-primary btn-sm">Search</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-2.5">RMA</th>
                        <th class="px-4 py-2.5">Order</th>
                        <th class="px-4 py-2.5">Customer</th>
                        <th class="px-4 py-2.5">Type</th>
                        <th class="px-4 py-2.5">Status</th>
                        <th class="px-4 py-2.5">Disputed</th>
                        <th class="px-4 py-2.5">Date</th>
                        <th class="px-4 py-2.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($returns as $return)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3 font-semibold">{{ $return->rma_number }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.index') }}?search={{ $return->order->invoice_id }}" class="text-brand">
                                    #{{ $return->order->invoice_id }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $return->user?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary">{{ $return->typeLabel() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white" style="background-color: {{ $return->statusColor() }}">
                                    {{ $return->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($return->is_disputed)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-feedback-danger text-white">Disputed</span>
                                @else
                                    <span class="text-ink-tertiary">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $return->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="eye" class="icon-xs"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-8 text-ink-tertiary">No return requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end px-4 py-3 border-t border-border">
            {{ $returns->links() }}
        </div>
    </div>
@endsection