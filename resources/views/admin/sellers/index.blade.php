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
                    <th scope="col">Country</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
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
                        <td> {{ $seller->country->name }} </td>
                        <td>{{ $seller->country->phone_code }} {{ $seller->phone }} </td>
                        <td> {{ $seller->email }} </td>

                        <td>
                            <div>
                                <button type="button"
                                    class="btn btn-light border  btn-sm mb-1 text-{{ $seller->is_best_seller ? 'danger' : 'success' }}"
                                    data-bs-toggle="modal" data-bs-target="#bestSellerModal{{ $seller->id }}">
                                    {{ $seller->is_best_seller ? 'Remove Best Seller' : 'Set Best Seller' }}
                                </button>
                                <button class="btn btn-light border  btn-sm mb-1" data-bs-toggle="modal"
                                    data-bs-target=""><i data-feather="edit" class="icon-xs"></i> Edit
                                </button>
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
