@extends('admin.layouts.app')
@section('title', 'Sellers')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Sellers</h4>
</div>

<div class="table-responsive">
    <table class="table table-bordered bg-white mb-3">
        <thead>
            <tr>
                <th scope="col">Seller</th>
                <th scope="col">Country</th>
                <th scope="col">Phone Code</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Business</th>
                <th scope="col">Registration Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($sellers as $seller)
            <tr>
                <td>
                    <x-user :user="$seller" />
                </td>
                <td> {{ $seller->country->name }} </td>
                <td> {{ $seller->country->phone_code }} </td>
                <td> {{ $seller->phone }} </td>
                <td> {{ $seller->email }} </td>
                <td>
                    <div class="d-flex">
                        <x-avatar :src="$seller->businessAvatar" />
                        <div class="ms-2">
                            {{ $seller->business_name }} <br>
                            <a href="" class="fw-bold link-primary small">{{ $seller->business_email }}</a> <br>
                            <small>Address: {{ $seller->business_address }}</small>
                        </div>
                    </div>

                </td>
                <td> {{ $seller->created_at->format('d/m/Y h:i A') }} </td>
                <td>
                    <div>
                        <button class="btn btn-light border  btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target=""><i data-feather="edit"
                                class="icon-xs"></i> Edit
                        </button>
                        <button class="btn btn-danger border btn-sm me-1 mb-1" data-bs-toggle="modal"
                            data-bs-target="">
                            <i data-feather="x-circle" class="icon-xs"></i> Block
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $sellers->links() }}

@endsection