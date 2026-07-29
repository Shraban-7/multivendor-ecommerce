@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')

<h3>Sellers</h3>

<?php
    $active = \App\Domain\Vendor\Models\Seller::ACTIVE;
    $pending = \App\Domain\Vendor\Models\Seller::PENDING;
    $blocked = \App\Domain\Vendor\Models\Seller::BLOCKED;
    $deleted = \App\Domain\Vendor\Models\Seller::DELETED;
?>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-3">
    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div class="flex w-auto grow" style="max-width: 350px;">
                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">
                    <i data-lucide="search"></i>
                </span>
                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search sellers..." />
            </div>
        </div>
    </div>
    <div class="p-5 p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
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
                            <div class="flex items-center">
                                <div class="seller-avatar me-2">
                                    <img src="{{ $seller->businessAvatar }}" height="36" width="36" style="object-fit:scale-down;" class="border">
                                </div>
                                <div>
                                    <h6 class="mb-1 font-semibold">{{ $seller->name }}</h6>
                                    <small class="text-ink-tertiary">{{ $seller->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <b>{{ $seller->business_name }}</b>
                        </td>
                        <td>
                            <small>{{ $seller->phone }}</small><br>
                            <small class="text-ink-tertiary">Joined: {{ $seller->created_at->format('M d, Y') }}</small>
                        </td>
                        <td class="text-center">
                            {{ $seller->commission_amount }}
                        </td>
                        <td class="text-center">
                            @if ($seller->status == $active)
                                <span class="badge text-bg-feedback-success">Active</span>
                            @elseif ($seller->status == $blocked)
                                <span class="badge text-bg-feedback-warning">Blocked</span>
                            @elseif ($seller->status == $pending)
                                <span class="badge text-bg-feedback-info">Pending</span>
                            @elseif($seller->status == $deleted)
                                <span class="badge text-bg-feedback-danger">Deleted</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a class="btn btn-primary btn-sm btn-icon me-1 mb-1" href="{{ route('admin.sellers.profile', $seller->username) }}">
                                <i data-lucide="eye"></i>
                            </a>
                            <button class="btn btn-light btn-sm mb-1" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $seller->id }}"><i data-lucide="edit" class="icon-xs"></i>
                            </button>
                        </td>
                    </tr>

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
                                                    {{ $seller->status == $pending ? 'selected' : '' }}>In
                                                    Active</option>
                                                <option value="1" {{ $seller->status == $active ? 'selected' : '' }}>
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="flex justify-end">
    {{ $sellers->links() }}
</div>

@endsection