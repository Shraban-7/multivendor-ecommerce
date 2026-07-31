@php
    $counts = $counts ?? ['total' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Manual Payment Methods')

@section('content')
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #14b8a6, #2dd4bf, #5eead4);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="wallet" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Infrastructure</span>
                    <i data-lucide="chevron-right" style="width:12px;height:14px;"></i>
                    <span class="text-ink-soft font-semibold">Manual Payment</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Manual Payment Methods</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#14b8a6]/15 text-[#14b8a6]">
                        <i data-lucide="wallet" style="width:11px;height:11px;" class="me-1"></i> Bank & Cash
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Bank-transfer and cash-on-delivery payment methods available at checkout.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="wallet" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Configured Methods</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Code</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Description</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($methods as $method)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#14b8a6]/15 flex items-center justify-center text-[#14b8a6] shrink-0">
                                    <i data-lucide="wallet" style="width:14px;height:14px;"></i>
                                </div>
                                <span class="font-semibold text-ink-emphasis">{{ $method->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $method->code }}</code>
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-soft" style="max-width:300px;">
                            <div class="truncate">{{ $method->description ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $method->id }}">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="py-10 text-center">
                                <i data-lucide="wallet" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No manual methods configured</p>
                                <p class="text-ink-tertiary text-xs">Configure bank-transfer or COD to allow offline payments.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@foreach ($methods as $method)
    <div class="modal fade" id="editModal{{ $method->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.manual-payment-methods.update', $method) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-bold">Edit Method</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Name</label>
                            <input type="text" name="name" value="{{ $method->name }}" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Code</label>
                            <input type="text" name="code" value="{{ $method->code }}" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Description</label>
                            <x-textarea-input name="description" :value="$method->description" rows="4" />
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
