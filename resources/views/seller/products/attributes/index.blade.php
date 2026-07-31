@php
    $allOptions = collect();
    $totalAttrs = $productAttributes->count();
    $totalOpts  = 0;
    foreach ($productAttributes as $a) { $totalOpts += $a->options->count(); }
@endphp
@extends('seller.layouts.app')
@section('title', 'Product Attributes')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #06b6d4, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="sliders" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Catalog</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Attributes</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Product Attributes</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#06b6d4]/15 text-[#06b6d4]">
                        <i data-lucide="list-tree" style="width:11px;height:11px;" class="me-1"></i> Custom Attributes
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage custom attributes (e.g. Material, Wattage) and their options.</p>
            </div>
        </div>
    </div>
</section>

@php
    $tiles = [
        ['label' => 'Attributes',      'value' => $totalAttrs, 'top' => '#06b6d4', 'text' => 'text-[#06b6d4]',         'icon' => 'list-tree'],
        ['label' => 'Options',         'value' => $totalOpts,  'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'sliders-horizontal'],
        ['label' => 'Latest Activity', 'value' => $productAttributes->first()?->created_at?->diffForHumans() ?? '—', 'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'clock', 'is_text' => true],
    ];
@endphp
<section class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">
                    @if($tile['is_text'] ?? false)
                        {{ $tile['value'] }}
                    @else
                        {{ number_format($tile['value']) }}
                    @endif
                </h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="list-tree" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Attributes</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Options</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productAttributes as $productAttribute)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors align-top">
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $productAttribute->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                @forelse ($productAttribute->options as $option)
                                    <div class="bg-surface-muted rounded-xs p-2 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-ink-emphasis">{{ $option->value }}</span>
                                        <button type="button" class="btn btn-light btn-sm text-feedback-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteOptionModal-{{ $option->id }}">
                                            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                        </button>
                                    </div>
                                    @php $allOptions->push($option); @endphp
                                @empty
                                    <span class="text-ink-tertiary text-xs">No options</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $productAttribute->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="btn btn-light btn-sm text-feedback-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productAttribute->id }}">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="py-10 text-center">
                                <i data-lucide="list-tree" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No custom attributes yet</p>
                                <p class="text-ink-tertiary text-xs">Define attributes like Material or Origin to enrich your products.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@foreach ($allOptions as $option)
    <div class="modal fade" id="deleteOptionModal-{{ $option->id }}" tabindex="-1"
         aria-labelledby="deleteOptionModalLabel-{{ $option->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold text-feedback-danger" id="deleteOptionModalLabel-{{ $option->id }}">Confirm Delete</h5>
                        <small class="text-ink-tertiary">{{ $option->value }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-feedback-warning/10 p-4 rounded-xs flex items-start gap-3">
                        <i data-lucide="circle-alert" class="text-feedback-warning shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                        <div class="text-sm text-ink-soft">Are you sure you want to delete this option?</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('seller.productAttributes.option_delete', $option->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($productAttributes as $productAttribute)
    <div class="modal fade" id="deleteModal-{{ $productAttribute->id }}" tabindex="-1"
         aria-labelledby="deleteModalLabel-{{ $productAttribute->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold text-feedback-danger" id="deleteModalLabel-{{ $productAttribute->id }}">Confirm Delete</h5>
                        <small class="text-ink-tertiary">{{ $productAttribute->name }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-feedback-danger/10 p-4 rounded-xs flex items-start gap-3">
                        <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                        <div class="text-sm text-ink-soft">Are you sure you want to delete this attribute and all its options?</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('seller.productAttributes.delete', $productAttribute->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
