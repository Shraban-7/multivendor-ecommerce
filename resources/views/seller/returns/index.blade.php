@extends('seller.layouts.app')
@section('title', 'Returns')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Return Management</h4>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm alert-dismissible fade show">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm alert-dismissible fade show">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 mb-4">
        @php
            $tickets = [
                ['key' => 'total',              'label' => 'Total',              'class' => 'ink'],
                ['key' => 'pending',            'label' => 'Pending',            'class' => 'feedback-warning'],
                ['key' => 'awaiting_shipment',  'label' => 'Awaiting Shipment',  'class' => 'feedback-info'],
                ['key' => 'approved',           'label' => 'Approved',           'class' => 'brand'],
                ['key' => 'received',           'label' => 'Item Received',      'class' => 'feedback-success'],
                ['key' => 'refunded',           'label' => 'Refunded',           'class' => 'ink-secondary'],
                ['key' => 'disputed',           'label' => 'Disputed',           'class' => 'feedback-danger'],
            ];
        @endphp
        @foreach ($tickets as $ticket)
            <div>
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                    <span class="text-ink-tertiary text-sm">{{ $ticket['label'] }}</span>
                    <h5 class="font-bold mb-0 text-{{ $ticket['class'] }}">{{ $counts[$ticket['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0">
        <div class="p-5">
            <div class="flex flex-wrap gap-2 mb-3 items-center">
                <a href="{{ route('seller.returns.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs focus:outline-none transition-colors gap-1 {{ !request('status') && !request('disputed') ? 'bg-ink text-white hover:bg-ink/90' : 'bg-surface-muted text-ink border border-border hover:bg-border/30' }}">ALL</a>
                @foreach (['pending' => 'Pending', 'awaiting_shipment' => 'Awaiting', 'approved' => 'Approved', 'item_received' => 'Received', 'refunded' => 'Refunded', 'rejected' => 'Rejected'] as $key => $label)
                    <a href="{{ route('seller.returns.index', ['status' => $key]) }}"
                       class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs focus:outline-none transition-colors gap-1 {{ request('status') === $key ? 'bg-brand-deep text-white hover:bg-brand' : 'bg-surface-muted text-ink border border-border hover:bg-border/30' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('seller.returns.index', ['disputed' => 1]) }}"
                   class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs focus:outline-none transition-colors gap-1 {{ request('disputed') ? 'bg-feedback-danger text-white hover:bg-feedback-danger/90' : 'bg-surface-muted text-ink border border-border hover:bg-border/30' }}">Disputed</a>

                <form method="GET" class="ms-auto flex gap-2">
                    <input type="text" name="search" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search RMA / Order / Customer" value="{{ request('search') }}">
                    <button class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Search</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover mb-0 align-middle">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th>RMA</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Items</th>
                            <th>Refund</th>
                            <th>Status</th>
                            <th>Dispute</th>
                            <th>Date</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td class="font-semibold">{{ $return->rma_number }}</td>
                                <td>#{{ $return->order->invoice_id }}</td>
                                <td>{{ $return->user?->name ?? 'N/A' }}<br><small class="text-ink-tertiary">{{ $return->user?->phone ?? '' }}</small></td>
                                <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink">{{ $return->typeLabel() }}</span></td>
                                <td class="text-sm">
                                    @foreach ($return->items as $item)
                                        <div>{{ $item->orderItem?->product?->name ?? 'Item' }} × {{ $item->quantity }}</div>
                                    @endforeach
                                </td>
                                <td>{{ number_format($return->totalRefundAmount(), 2) }}</td>
                                <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $return->statusColor() }}">{{ $return->label() }}</span></td>
                                <td>
                                    @if ($return->is_disputed)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-danger text-white" title="{{ $return->dispute?->status?->label() ?? 'Open' }}">Disputed</span>
                                    @else<span class="text-ink-tertiary">—</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $return->created_at->format('d/m/Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('seller.returns.show', $return) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-4 text-ink-tertiary">No return requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-3">
                {{ $returns->links() }}
            </div>
        </div>
    </div>
@endsection
