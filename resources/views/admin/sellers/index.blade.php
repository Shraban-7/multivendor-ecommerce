@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')

<h3>Sellers</h3>

<?php
    $active = \App\Models\Seller::ACTIVE;
    $pending = \App\Models\Seller::PENDING;
    $blocked = \App\Models\Seller::BLOCKED;
    $deleted = \App\Models\Seller::DELETED;
?>

<div class="card mb-3">
    <div class="card-header bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="input-group w-auto flex-grow-1" style="max-width: 350px;">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Search sellers..." />
            </div>
            <select class="form-select w-auto">
                <option>All Status</option>
                <option>Approved</option>
                <option>Pending</option>
                <option>Rejected</option>
            </select>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="py-3 px-4">Seller</th>
                        <th scope="col" class="py-3">Shop Name</th>
                        <th scope="col" class="py-3">Contact</th>
                        <th scope="col" class="py-3 text-center">Commission</th>
                        <th scope="col" class="py-3 text-center">Status</th>
                        <th scope="col" class="py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sellers as $seller)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="seller-avatar me-2">
                                    <img src="{{ $seller->businessAvatar }}" height="36" width="36" style="object-fit:scale-down;" class="border">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $seller->name }}</h6>
                                    <small class="text-muted">{{ $seller->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <b>{{ $seller->business_name }}</b>
                        </td>
                        <td>
                            <small>{{ $seller->phone }}</small><br>
                            <small class="text-muted">Joined: {{ $seller->created_at->format('M d, Y') }}</small>
                        </td>
                        <td class="text-center">
                            {{ $seller->commission_amount }}
                        </td>
                        <td class="text-center">
                            @if ($seller->status == $active)
                                <span class="badge text-bg-success">Active</span>
                            @elseif ($seller->status == $blocked)
                                <span class="badge text-bg-warning">Blocked</span>
                            @elseif ($seller->status == $pending)
                                <span class="badge text-bg-info">Pending</span>
                            @elseif($seller->status == $deleted)
                                <span class="badge text-bg-danger">Deleted</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary btn-sm-icon me-1 mb-1" href="{{ route('admin.sellers.profile', $seller->username) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-light border btn-sm mb-1" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $seller->id }}"><i data-feather="edit" class="icon-xs"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal-{{ $seller->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Information</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="commission" class="form-label">Commission</label>
                                            <div class="input-group">
                                                <select name="commission_type" id="commission_type" class="form-select"
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
                                                    name="commission_amount" id="commission_amount" class="form-control"
                                                    placeholder="Amount" value="{{ $seller->commission_amount }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit-name" class="form-label">Status</label>
                                            <select name="status" id="is_active" class="form-select">
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
                                        <button type="button" class="btn btn-secondary"
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

<div class="d-flex justify-content-end">
    {{ $sellers->links() }}
</div>

@endsection