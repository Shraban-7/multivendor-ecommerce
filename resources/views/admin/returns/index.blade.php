@extends('admin.layouts.app')
@section('title', 'Return Management')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Return Management</h4>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-3 mb-4">
        @php
            $summaryCards = [
                ['key' => 'total',             'label' => 'Total',            'class' => 'dark'],
                ['key' => 'pending',           'label' => 'Pending',          'class' => 'warning'],
                ['key' => 'awaiting_shipment', 'label' => 'Awaiting',         'class' => 'info'],
                ['key' => 'approved',          'label' => 'Approved',         'class' => 'primary'],
                ['key' => 'rejected',          'label' => 'Rejected',         'class' => 'danger'],
                ['key' => 'refunded',          'label' => 'Refunded',         'class' => 'success'],
                ['key' => 'disputed',          'label' => 'Disputed',         'class' => 'danger'],
            ];
        @endphp
        @foreach ($summaryCards as $card)
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3">
                    <span class="text-ink-tertiary text-sm">{{ $card['label'] }}</span>
                    <h5 class="font-bold mb-0 text-{{ $card['class'] }}">{{ $counts[$card['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
        <div class="p-5">
            <div class="flex flex-wrap gap-2 mb-3">
                <a href="{{ route('admin.returns.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ !request('status') && !request('disputed') ? 'btn-dark' : 'btn-light border' }}">All</a>
                <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ request('status') === 'pending' ? 'btn-warning' : 'btn-light border' }}">Pending</a>
                <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ request('status') === 'approved' ? 'btn-success' : 'btn-light border' }}">Approved</a>
                <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light border' }}">Rejected</a>
                <a href="{{ route('admin.returns.index', ['status' => 'refunded']) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ request('status') === 'refunded' ? 'btn-info' : 'btn-light border' }}">Refunded</a>
                <a href="{{ route('admin.returns.index', ['disputed' => 1]) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ request('disputed') ? 'btn-danger' : 'btn-light border' }}">Disputed</a>

                <form method="GET" class="ms-auto flex gap-2">
                    <input type="text" name="search" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Search RMA, order, customer..." value="{{ request('search') }}">
                    <button class="btn btn-primary btn-sm">Search</button>
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
                            <th>Status</th>
                            <th>Disputed</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td class="font-semibold">{{ $return->rma_number }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.index') }}?search={{ $return->order->invoice_id }}" class="text-brand">
                                        #{{ $return->order->invoice_id }}
                                    </a>
                                </td>
                                <td>{{ $return->user?->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-surface-muted">{{ $return->typeLabel() }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $return->statusColor() }}">
                                        {{ $return->label() }}
                                    </span>
                                </td>
                                <td>
                                    @if ($return->is_disputed)
                                        <span class="badge bg-feedback-danger">Disputed</span>
                                    @else
                                        <span class="text-ink-tertiary">—</span>
                                    @endif
                                </td>
                                <td class="small">{{ $return->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-ink-tertiary">No return requests found.</td></tr>
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
