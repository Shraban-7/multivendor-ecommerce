@extends('admin.layouts.app')

@section('title', 'Seller Subscriptions')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Subscriptions</h3>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Trial</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'trial')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Expired</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->where('status', 'expired')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscriptions->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Search Seller</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Name or email"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Plan</label>
                            <select name="plan_id" class="form-control">
                                <option value="">All Plans</option>
                                @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
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
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Subscriptions List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
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
                                <small class="text-muted">{{ $subscription->seller->email }}</small>
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
                                        class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($subscription->status === 'active' || $subscription->status === 'trial')
                                    <button type="button" class="btn btn-sm btn-warning"
                                        data-toggle="modal"
                                        data-target="#suspendModal{{ $subscription->id }}"
                                        title="Suspend">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    @endif

                                    @if($subscription->status === 'suspended')
                                    <form action="{{ route('admin.subscriptions.activate', $subscription) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
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
                                                <label>Reason for Suspension <span class="text-danger">*</span></label>
                                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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