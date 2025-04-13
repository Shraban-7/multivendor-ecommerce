@extends('seller.layouts.app')
@section('title', 'Customers')
@section('content')

    <div class="mb-2 rounded">
        <h4 class="mb-0">Customers</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Country</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <img src="{{ storage_url($user->image) ?? asset('default-user.png') }}" alt="User Image"
                                width="40" height="40" class="rounded-circle object-fit-cover">
                            {{ $user->fullname }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user?->country?->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
