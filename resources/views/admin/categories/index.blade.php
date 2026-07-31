@php
    $counts = $counts ?? ['total' => 0, 'active' => 0, 'sub' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="folder-tree" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Catalog</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Categories</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Categories</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="folder-tree" style="width:11px;height:11px;" class="me-1"></i> Taxonomy
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Organise your product catalog with top-level categories and subcategories.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Category
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Flash --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',  'label' => 'Categories',     'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'folder'],
        ['key' => 'active', 'label' => 'Active',          'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'sub',    'label' => 'Subcategories',   'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'folder-tree'],
    ];
@endphp
<section class="grid grid-cols-3 gap-3 mb-3">
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

{{-- ═══ TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="folder-tree" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Category Tree</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Image</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <img src="{{ storage_url($category->image) }}" alt=""
                                 width="40" height="40"
                                 style="object-fit:cover;border-radius:6px;" class="shrink-0">
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($category->status)
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
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </button>
                        </td>
                    </tr>
                    @foreach($category->subcategories as $sub)
                        <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="corner-down-right" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                                    <span class="text-ink-soft font-medium">{{ $sub->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($sub->status)
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
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $sub->id }}">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="py-10 text-center">
                                <i data-lucide="folder-plus" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No categories yet</p>
                                <p class="text-ink-tertiary text-xs">Add a category to start organising the catalog.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@foreach($categories as $item)
    @include('admin.categories.edit_modal', ['category' => $item])
    @foreach($item->subcategories as $sub)
        @include('admin.categories.edit_modal', ['category' => $sub])
    @endforeach
@endforeach

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold" id="modalTitle">Add Category</h5>
                        <small class="text-ink-tertiary">Create a new top-level category or subcategory</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Name</label>
                        <input type="text" name="name" id="cat_name"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Parent Category <span class="font-normal normal-case text-ink-tertiary">(optional)</span></label>
                        <select name="category_id" id="cat_parent"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">None (Main)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Image</label>
                        <input type="file" name="image"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                    <div class="flex items-center gap-2">
                        <input class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep" type="checkbox" name="status" value="1" id="cat_status" checked>
                        <label class="text-sm text-ink-emphasis" for="cat_status">Is Active</label>
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

@endsection
