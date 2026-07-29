@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')

<div class="flex justify-between items-start mb-4">
        <div>
            <h4 class="font-semibold">Pending Sellers</h4>
            <p class="text-sm text-ink-secondary mt-1">Sellers awaiting approval</p>
        </div>
        <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="icon-xs me-1"></i> Add Seller
        </a>
    </div>

    @php
        $active_status = \App\Domain\Vendor\Models\Seller::ACTIVE;
        $pending_status = \App\Domain\Vendor\Models\Seller::PENDING;
        $blocked_status = \App\Domain\Vendor\Models\Seller::BLOCKED;
        $deleted_status = \App\Domain\Vendor\Models\Seller::DELETED;
    @endphp

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.sellers.pending') }}" class="btn btn-light btn-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col" class="py-3 px-4">Seller</th>
                    <th scope="col" class="py-3">Shop Name</th>
                    <th scope="col" class="py-3">Contact</th>
                    <th scope="col" class="py-3 text-center">Commission</th>
                    <th scope="col" class="py-3 text-center">Status</th>
                    <th scope="col" class="py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sellers as $seller)
                <tr>
                    <td class="px-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $seller->businessAvatar }}" height="36" width="36" style="object-fit:scale-down;" class="border rounded">
                            <div>
                                <div class="font-semibold">{{ $seller->name }}</div>
                                <div class="text-xs text-ink-tertiary">{{ $seller->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="font-semibold">{{ $seller->business_name }}</td>
                    <td>
                        <div class="text-sm">{{ $seller->phone }}</div>
                        <div class="text-xs text-ink-tertiary">Joined: {{ $seller->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="text-center">{{ $seller->commission_amount }}</td>
                    <td class="text-center">
                        @if ($seller->status == $active_status)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Active</span>
                        @elseif ($seller->status == $blocked_status)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Blocked</span>
                        @elseif ($seller->status == $pending_status)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-blue-500 rounded-full">Pending</span>
                        @elseif($seller->status == $deleted_status)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full">Deleted</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.sellers.profile', $seller->username) }}">
                            <i data-lucide="eye" class="icon-xs"></i> <span>View</span>
                        </a>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editModal-{{ $seller->id }}"><i data-lucide="edit" class="icon-xs"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<div class="flex justify-end">
    {{ $sellers->links() }}
</div>

@push('modals')
    @foreach ($sellers as $seller)
        <div class="modal fade" id="editModal-{{ $seller->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Commission and Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.sellers.updateStatus', $seller->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="commission" class="block text-xs font-medium text-ink-secondary mb-1">Commission</label>
                                <div class="flex">
                                    <select name="commission_type" id="commission_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                        style="max-width: 140px;">
                                        <option value="" disabled
                                            {{ $seller->commission_type === null ? 'selected' : '' }}>Type
                                        </option>
                                        <option value="{{ \App\Enums\CommissionType::FLAT->value }}"
                                            {{ \App\Enums\CommissionType::FLAT->value == $seller->commission_type ? 'selected' : '' }}>
                                            {{ ucfirst(\App\Enums\CommissionType::FLAT->label()) }}
                                        </option>
                                        <option value="{{ \App\Enums\CommissionType::PERCENTAGE->value }}"
                                            {{ \App\Enums\CommissionType::PERCENTAGE->value == $seller->commission_type ? 'selected' : '' }}>
                                            {{ ucfirst(\App\Enums\CommissionType::PERCENTAGE->label()) }}
                                        </option>
                                    </select>
                                    <input type="number" min="0" max="100"
                                        name="commission_amount" id="commission_amount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                        placeholder="Amount" value="{{ $seller->commission_amount }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit-name" class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                                <select name="status" id="is_active" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="0"
                                        {{ $seller->status == $pending_status ? 'selected' : '' }}>In
                                        Active</option>
                                    <option value="1" {{ $seller->status == $active_status ? 'selected' : '' }}>
                                        Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endpush
@endsection