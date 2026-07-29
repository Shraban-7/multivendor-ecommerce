@extends('admin.layouts.app')

@section('title', 'Seller Subscriptions')

@section('content')
<div class="container-fluid">
    <div class="grid grid-cols-1 mb-3">
        <div class="md:col-span-full">
            <div class="flex justify-between items-center">
                <h3 class="mb-0">Subscriptions</h3>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 mb-4">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-left-primary h-full py-2">
                <div class="p-5">
                    <div class="grid grid-cols-1 no-gutters items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-brand uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-brand"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-left-warning h-full py-2">
                <div class="p-5">
                    <div class="grid grid-cols-1 no-gutters items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-feedback-warning uppercase mb-1">Trial</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'trial')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-feedback-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-left-danger h-full py-2">
                <div class="p-5">
                    <div class="grid grid-cols-1 no-gutters items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-feedback-danger uppercase mb-1">Expired</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'expired')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-feedback-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-left-info h-full py-2">
                <div class="p-5">
                    <div class="grid grid-cols-1 no-gutters items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-feedback-info uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-feedback-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow mb-4">
        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between py-3">
            <h6 class="m-0 font-weight-bold text-brand">Filters</h6>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}">
                <div class="grid grid-cols-1">
                    <div class="md:col-span-1">
                        <div class="form-group">
                            <label>Search Seller</label>
                            <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                placeholder="Name or email"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="form-group">
                            <label>Plan</label>
                            <select name="plan_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                <option value="">All Plans</option>
                                @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Subscriptions Table --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow mb-4">
        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between py-3">
            <h6 class="m-0 font-weight-bold text-brand">Subscriptions List</h6>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover" id="dataTable">
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
                                <strong>{{ $subscription->seller->name }}</strong><br>
                                <small class="text-ink-tertiary">{{ $subscription->seller->email }}</small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $subscription->plan->name }}</span>
                                @if($subscription->is_trial)
                                <span class="badge badge-warning">Trial</span>
                                @endif
                            </td>
                            <td>
                                @php
                                $statusColors = [
                                'active' => 'success',
                                'trial' => 'warning',
                                'expired' => 'danger',
                                'cancelled' => 'secondary',
                                'suspended' => 'dark'
                                ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$subscription->status] ?? 'secondary' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td>{{ $subscription->start_date->format('d M, Y') }}</td>
                            <td>{{ $subscription->end_date->format('d M, Y') }}</td>
                            <td>
                                @php
                                $daysLeft = $subscription->daysRemaining();
                                @endphp
                                @if($daysLeft > 0)
                                <span class="badge badge-{{ $daysLeft <= 3 ? 'danger' : 'success' }}">
                                    {{ $daysLeft }} days
                                </span>
                                @else
                                <span class="badge badge-secondary">Expired</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}"
                                        class="btn btn-info btn-sm hover:bg-blue-700" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href=""
                                        class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($subscription->status === 'active' || $subscription->status === 'trial')
                                    <button type="button" class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#suspendModal{{ $subscription->id }}"
                                        title="Suspend">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    @endif

                                    @if($subscription->status === 'suspended')
                                    <form action="{{ route('admin.subscriptions.activate', $subscription) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Activate this subscription?')"
                                            title="Activate">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Suspend Modal --}}
                        <div class="modal fade" id="suspendModal{{ $subscription->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.subscriptions.suspend', $subscription) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Suspend Subscription</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Reason for Suspension <span class="text-feedback-danger">*</span></label>
                                                <textarea name="reason" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">Suspend</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No subscriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('[title]').tooltip();
    });
</script>
@endpush