@php
    $counts = $counts ?? ['total' => 0, 'active' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Brands')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #06b6d4, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="tag" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Catalog</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Brands</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Brands</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="trophy" style="width:11px;height:11px;" class="me-1"></i> Taxonomy
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Define the brands sellers can tag their products with.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Brand
                </button>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

@php
    $tiles = [
        ['key' => 'total',  'label' => 'Total Brands',  'top' => '#06b6d4', 'text' => 'text-[#06b6d4]',         'icon' => 'tag'],
        ['key' => 'active', 'label' => 'Active',          'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
    ];
@endphp
<section class="grid grid-cols-2 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="tag" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Brands</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary w-12">#</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Logo</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($brands as $brand)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-ink-tertiary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ storage_url($brand->image) }}" alt="" width="40" height="40"
                                 style="object-fit:contain;border-radius:6px;" class="bg-surface-muted p-1 shrink-0">
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $brand->name }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($brand->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $brand->id }}">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="py-10 text-center">
                                <i data-lucide="tag" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No brands yet</p>
                                <p class="text-ink-tertiary text-xs">Add a brand to enable tagging.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($brands->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $brands->links() }}
        </div>
    @endif
</section>

{{-- Add Brand Modal --}}
<div class="modal fade" id="brandModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-bold">Add Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Logo</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep">
                    </div>
                    <div class="flex items-center gap-2">
                        <input class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep" type="checkbox" name="status" value="1" id="brandStatus" checked>
                        <label class="text-sm text-ink-emphasis" for="brandStatus">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach ($brands as $brand)
    <div class="modal fade" id="editModal{{ $brand->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-bold">Edit Brand — {{ $brand->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Name</label>
                            <input type="text" name="name" value="{{ $brand->name }}" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Replace Logo <span class="font-normal normal-case text-ink-tertiary">(optional)</span></label>
                            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep">
                        </div>
                        <div class="flex items-center gap-2">
                            <input class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep" type="checkbox" name="status" value="1" id="brandStatus{{ $brand->id }}" @checked($brand->status)>
                            <label class="text-sm text-ink-emphasis" for="brandStatus{{ $brand->id }}">Is Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:14px;height:14px;"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
