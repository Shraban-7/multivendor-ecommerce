@extends('admin.layouts.app')
@section('title', 'Customers')
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Customers</h1>
            <p class="text-sm text-ink-secondary mt-1">View all registered customers</p>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.customers.index') }}">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col">Customer</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Registration Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                <tr>
                    <td>
                        <x-user :user="$customer" />
                    </td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->email }}</td>
                    <td class="text-ink-tertiary text-xs">{{ $customer->created_at->format('d/m/Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-ink-tertiary">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        {{ $customers->links() }}
    </div>

@endsection