@extends('admin.layouts.app')

@section('title', 'Seller Subscriptions')

@section('content')
<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink">Seller Subscriptions</h1>
        <p class="text-sm text-ink-secondary mt-1">Manage seller subscription plans and statuses</p>
    </div>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-brand uppercase tracking-wider mb-1">Active</div>
                <div class="text-2xl font-bold text-ink">{{ $subscriptions->where('status', 'active')->count() }}</div>
            </div>
            <i data-lucide="circle-check" class="text-brand" style="width:28px;height:28px;"></i>
        </div>
    </div>
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-feedback-warning uppercase tracking-wider mb-1">Trial</div>
                <div class="text-2xl font-bold text-ink">{{ $subscriptions->where('status', 'trial')->count() }}</div>
            </div>
            <i data-lucide="gift" class="text-feedback-warning" style="width:28px;height:28px;"></i>
        </div>
    </div>
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-feedback-danger uppercase tracking-wider mb-1">Expired</div>
                <div class="text-2xl font-bold text-ink">{{ $subscriptions->where('status', 'expired')->count() }}</div>
            </div>
            <i data-lucide="circle-x" class="text-feedback-danger" style="width:28px;height:28px;"></i>
        </div>
    </div>
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-feedback-info uppercase tracking-wider mb-1">Total</div>
                <div class="text-2xl font-bold text-ink">{{ $subscriptions->total() }}</div>
            </div>
            <i data-lucide="users" class="text-feedback-info" style="width:28px;height:28px;"></i>
        </div>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
        <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Filters</h6>
    </div>
    <div class="p-4">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}">
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                        placeholder="Search by seller name or email..." value="{{ request('search') }}">
                </div>
                <div class="w-44">
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="w-44">
                    <select name="plan_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All Plans</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-lucide="search" class="icon-xs"></i> Filter
                </button>
                @if(request('search') || request('status') || request('plan_id'))
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-light btn-sm">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
        <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Subscriptions List</h6>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Seller</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days Left</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->id }}</td>
                        <td>
                            <strong class="text-ink">{{ $subscription->seller->name }}</strong><br>
                            <small class="text-ink-tertiary">{{ $subscription->seller->email }}</small>
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-feedback-info rounded-full">{{ $subscription->plan->name }}</span>
                            @if($subscription->is_trial)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Trial</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $statusColors = [
                                'active' => 'bg-green-500',
                                'trial' => 'bg-yellow-400',
                                'expired' => 'bg-red-500',
                                'cancelled' => 'bg-surface-strong',
                                'suspended' => 'bg-ink-tertiary'
                            ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white {{ $statusColors[$subscription->status] ?? 'bg-ink-tertiary' }} rounded-full">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="text-ink-secondary text-xs">{{ $subscription->start_date->format('d M, Y') }}</td>
                        <td class="text-ink-secondary text-xs">{{ $subscription->end_date->format('d M, Y') }}</td>
                        <td>
                            @php
                            $daysLeft = $subscription->daysRemaining();
                            @endphp
                            @if($daysLeft > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white {{ $daysLeft <= 3 ? 'bg-red-500' : 'bg-green-500' }} rounded-full">
                                {{ $daysLeft }} days
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Expired</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}"
                                    class="btn btn-sm btn-light" title="View Details">
                                    <i data-lucide="eye" class="icon-xs"></i>
                                </a>
                                @if($subscription->status === 'active' || $subscription->status === 'trial')
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#suspendModal{{ $subscription->id }}"
                                    title="Suspend">
                                    <i data-lucide="pause" class="icon-xs"></i>
                                </button>
                                @endif
                                @if($subscription->status === 'suspended')
                                <form action="{{ route('admin.subscriptions.activate', $subscription) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Activate this subscription?')"
                                        title="Activate">
                                        <i data-lucide="play" class="icon-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-ink-tertiary">No subscriptions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mt-4">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>

@foreach($subscriptions as $subscription)
<div class="modal fade" id="suspendModal{{ $subscription->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.subscriptions.suspend', $subscription) }}" method="POST">
                @csrf
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink">Suspend Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Reason for Suspension <span class="text-feedback-danger">*</span></label>
                        <textarea name="reason" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-t border-border">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection