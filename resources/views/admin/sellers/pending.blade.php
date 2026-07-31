@php
    use App\Domain\Vendor\Models\Seller;
    $counts = $counts ?? ['total' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Pending Sellers')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #fb923c, #fdba74, #fed7aa);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="user-cog" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Reach</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Pending Sellers</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Pending Sellers</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="hourglass" style="width:11px;height:11px;" class="me-1"></i> Awaiting Approval
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Approve or reject newly-registered sellers awaiting onboarding.</p>
            </div>
            <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">
                <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Seller
            </a>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('search'))
            <a href="{{ route('admin.sellers.pending') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-10 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name, email, or phone…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Search
                </button>
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $sellers->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $sellers->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $sellers->total() }}</span> pending sellers
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Seller</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Shop</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Contact</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Commission</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sellers as $seller)
                    @php
                        $pillBg = match ((int) $seller->status) {
                            (int) Seller::PENDING  => 'bg-feedback-warning/15 text-feedback-warning',
                            (int) Seller::ACTIVE   => 'bg-feedback-success/15 text-feedback-success',
                            (int) Seller::BLOCKED  => 'bg-feedback-danger/15 text-feedback-danger',
                            (int) Seller::DELETED  => 'bg-ink-tertiary/15 text-ink-secondary',
                            default                => 'bg-surface-muted text-ink-tertiary',
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $seller->businessAvatar }}" alt="" width="40" height="40"
                                     style="object-fit:cover;border-radius:8px;" class="shrink-0">
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm">{{ $seller->name }}</div>
                                    <div class="text-xs text-ink-tertiary">{{ $seller->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $seller->business_name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="inline-flex items-center gap-1 text-ink-soft">
                                <i data-lucide="phone" style="width:11px;height:11px;" class="text-ink-tertiary"></i>
                                {{ $seller->phone ?? '—' }}
                            </div>
                            <small class="text-ink-tertiary block mt-0.5">Joined {{ $seller->created_at->format('M d, Y') }}</small>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold text-ink-emphasis">{{ $seller->commission_amount }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ match ((int) $seller->status) { (int) Seller::PENDING => 'Pending', (int) Seller::ACTIVE => 'Active', (int) Seller::BLOCKED => 'Blocked', (int) Seller::DELETED => 'Deleted', default => ucfirst((string) $seller->status) } }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.sellers.profile', $seller->username) }}">
                                    <i data-lucide="eye" style="width:13px;height:13px;"></i> View
                                </a>
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $seller->id }}">
                                    <i data-lucide="settings" style="width:13px;height:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-10 text-center">
                                <i data-lucide="check-circle-2" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No pending sellers</p>
                                <p class="text-ink-tertiary text-xs">All caught up — nothing awaiting your approval.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $sellers->links() }}
    </div>
</section>

@push('modals')
    @foreach ($sellers as $seller)
        <div class="modal fade" id="editModal-{{ $seller->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold">Edit Commission and Status</h5>
                            <small class="text-ink-tertiary">{{ $seller->name }} · {{ $seller->business_name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.sellers.updateStatus', $seller->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Commission</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <select name="commission_type"
                                            class="px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                        <option value="" disabled @selected($seller->commission_type === null)>Type</option>
                                        <option value="{{ \App\Enums\CommissionType::FLAT->value }}"
                                                @selected(\App\Enums\CommissionType::FLAT->value == $seller->commission_type)>
                                            {{ ucfirst(\App\Enums\CommissionType::FLAT->label()) }}
                                        </option>
                                        <option value="{{ \App\Enums\CommissionType::PERCENTAGE->value }}"
                                                @selected(\App\Enums\CommissionType::PERCENTAGE->value == $seller->commission_type)>
                                            {{ ucfirst(\App\Enums\CommissionType::PERCENTAGE->label()) }}
                                        </option>
                                    </select>
                                    <input type="number" min="0" max="100" name="commission_amount"
                                           value="{{ $seller->commission_amount }}"
                                           placeholder="Amount"
                                           class="col-span-2 w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Status</label>
                                <select name="status"
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="0" @selected($seller->status == Seller::PENDING)>Inactive</option>
                                    <option value="1" @selected($seller->status == Seller::ACTIVE)>Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endpush

@endsection
