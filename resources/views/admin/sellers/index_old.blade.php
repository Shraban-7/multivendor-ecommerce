@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')
    @php
        $active = \App\Domain\Vendor\Models\Seller::ACTIVE;
        $pending = \App\Domain\Vendor\Models\Seller::PENDING;
        $blocked = \App\Domain\Vendor\Models\Seller::BLOCKED;
        $deleted = \App\Domain\Vendor\Models\Seller::DELETED;
    @endphp

    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Sellers</h4>
    </div>

    <div class="table-responsive">
        <table id="seller-table" class="table table-bordered bg-white mb-3">
            <thead>
                <tr>
                    <th scope="col">Shop</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sellers as $seller)
                    @php
                        $isBlocked = $seller->status == $blocked;
                        $modalId = $isBlocked ? "unblockSellerModal{$seller->id}" : "blockSellerModal{$seller->id}";
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex">
                                <x-avatar :src="$seller->businessAvatar" :size="'medium'" />
                                <div class="ms-2">
                                    {{ $seller->business_name }} <br>
                                    <small class="small">{{ $seller->business_email }}</small>
                                    <br>
                                    <small>Address: {{ $seller->business_address }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex ">
                                <x-avatar :src="$seller->avatar" :size="'medium'" />
                                <div class="ms-2">
                                    <a href="{{ route('admin.sellers.profile', $seller->username) }}" target="__blank"
                                        class="fw-bold link-primary small">
                                        {{ $seller->name }}
                                    </a> <br>
                                    <small class="text-muted">{{ $seller->phone }}</small>
                                    <br>
                                    <small class="text-muted">{{ $seller->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                {{ $seller->commission_amount }}
                                <button class="btn btn-light border  btn-sm mb-1" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $seller->id }}"><i data-feather="edit"
                                        class="icon-xs"></i>
                                    Edit
                                </button>
                            </div>
                        </td>
                        <td>
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
                        <td>
                            <div>
                                @if (hasPermission('admin.sellers.best_seller'))
                                    <button type="button"
                                        class="btn btn-light border  btn-sm mb-1 text-{{ $seller->is_best_seller ? 'danger' : 'success' }}"
                                        data-bs-toggle="modal" data-bs-target="#bestSellerModal{{ $seller->id }}">
                                        {{ $seller->is_best_seller ? 'Remove Best Seller' : 'Set Best Seller' }}
                                    </button>
                                @endif

                                <!-- Block Button -->
                                <button type="button"
                                    class="btn btn-{{ $isBlocked ? 'success' : 'danger' }} border btn-sm me-1 mb-1"
                                    data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                    <i data-feather="{{ $isBlocked ? 'check-circle' : 'x-circle' }}" class="icon-xs"></i>
                                    {{ $isBlocked ? 'Unblock' : 'Block' }}
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Block/Unblock Modal -->
                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        {{ $isBlocked ? 'Confirm Unblock Seller' : 'Confirm Block Seller' }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <p>
                                        Are you sure you want to
                                        <strong>{{ $isBlocked ? 'unblock' : 'block' }}</strong>
                                        seller <strong>{{ $seller->business_name }}</strong>?
                                    </p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                    <form action="{{ route('admin.sellers.toggleBlock', $seller->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $isBlocked ? 1 : 2 }}">
                                        <button type="submit" class="btn btn-{{ $isBlocked ? 'success' : 'danger' }}">
                                            Yes, {{ $isBlocked ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Best Seller Modal -->
                    <div class="modal fade" id="bestSellerModal{{ $seller->id }}" tabindex="-1"
                        aria-labelledby="bestSellerModalLabel{{ $seller->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('admin.sellers.best_seller', $seller->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="is_best_seller"
                                        value="{{ $seller->is_best_seller ? 0 : 1 }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bestSellerModalLabel{{ $seller->id }}">
                                            {{ $seller->is_best_seller ? 'Remove Best Seller' : 'Set as Best Seller' }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to {{ $seller->is_best_seller ? 'remove' : 'set' }} this
                                        seller as
                                        {{ $seller->is_best_seller ? 'Best Seller' : 'Best Seller' }}?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit"
                                            class="btn {{ $seller->is_best_seller ? 'btn-danger' : 'btn-success' }}">
                                            Yes, {{ $seller->is_best_seller ? 'Remove' : 'Set' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal-{{ $seller->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-3">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Information</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="commission" class="form-label">Commission</label>
                                            <div class="input-group">
                                                <!-- Commission Type Select -->
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

                                                <!-- Commission Amount Input -->
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

    @push('scripts')
        <script>
            new DataTable('#seller-table');
        </script>
    @endpush

@endsection
