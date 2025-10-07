@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')

    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Sellers</h4>
    </div>

    <div class="table-responsive">
        <table id="seller-table" class="table table-bordered bg-white mb-3">
            <thead>
                <tr>
                    <th scope="col">Shop</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sellers as $seller)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <x-avatar :src="$seller->businessAvatar" />
                                <div class="ms-2">
                                    {{ $seller->business_name }} <br>
                                    <a href="" class="fw-bold link-primary small">{{ $seller->business_email }}</a>
                                    <br>
                                    <small>Address: {{ $seller->business_address }}</small>
                                </div>
                            </div>

                        </td>
                        <td>
                            <x-seller :seller="$seller" />
                        </td>
                        <td>{{ $seller->phone }} </td>
                        <td> {{ $seller->email }} </td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                {{ $seller->commission_amount }}
                                <button class="btn btn-light border  btn-sm mb-1" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $seller->id }}"><i data-feather="edit" class="icon-xs"></i>
                                    Edit
                                </button>
                            </div>
                        </td>
                        <td> <span
                                class="badge text-bg-{{ $seller->is_active == 1 ? 'success' : 'warning' }}">{{ $seller->is_active == 1 ? 'Active' : 'In Active' }}</span>
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

                                <button class="btn btn-danger border btn-sm me-1 mb-1" data-bs-toggle="modal"
                                    data-bs-target="">
                                    <i data-feather="x-circle" class="icon-xs"></i> Block
                                </button>
                            </div>
                        </td>
                    </tr>

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
                                                <input type="number" min="0" max="100" name="commission_amount" id="commission_amount"
                                                    class="form-control" placeholder="Amount"
                                                    value="{{ $seller->commission_amount  }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit-name" class="form-label">Status</label>
                                            <select name="is_active" id="is_active" class="form-select">
                                                <option value="0" {{ $seller->is_active == 0 ? 'selected' : '' }}>In
                                                    Active</option>
                                                <option value="1" {{ $seller->is_active == 1 ? 'selected' : '' }}>
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
