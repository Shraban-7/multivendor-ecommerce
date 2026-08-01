@php
    $priorityCases = \App\Domain\Support\Enums\TicketPriority::cases();
@endphp
@extends('seller.layouts.app')
@section('title', 'Open New Ticket')

@section('content')

    {{-- ═══ HERO ═══ --}}
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);">
        </div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="message-circle" class="text-feedback-info" style="width:12px;height:12px;"></i>
                        <a href="{{ route('seller.support.index') }}" class="hover:text-ink-emphasis">Support</a>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">New Ticket</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Open a New Ticket</h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                            <i data-lucide="edit-3" style="width:11px;height:11px;" class="me-1"></i> Drafting
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Provide as much detail as possible so our admin team can help
                        quickly.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('seller.support.index') }}" class="btn btn-light">
                        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back to tickets
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ FORM CARD ═══ --}}
    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="p-5">
            <form method="POST" action="{{ route('seller.support.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    {{-- Subject --}}
                    <div class="md:col-span-8">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                            Subject
                        </label>
                        <div class="relative">
                            <i data-lucide="type" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary"
                                style="width:14px;height:14px; left: 10px;"></i>
                            <input type="text" name="subject" maxlength="200" value="{{ old('subject') }}" required
                                placeholder="Short, descriptive summary…"
                                class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                        @error('subject')
                            <div class="text-feedback-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                            Priority
                        </label>
                        <div class="relative">
                            <i data-lucide="flag" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary"
                                style="width:14px;height:14px; left: 10px;"></i>
                            <select name="priority" required
                                class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep appearance-none transition-colors">
                                @foreach ($priorityCases as $p)
                                    <option value="{{ $p->value }}" @selected(old('priority', 'normal') === $p->value)>{{ $p->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="md:col-span-6">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                            Category
                        </label>
                        <div class="relative">
                            <i data-lucide="tag" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary"
                                style="width:14px;height:14px; left: 10px;"></i>
                            <select name="category" required
                                class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep appearance-none transition-colors">
                                @foreach ($categories as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category', 'other') === $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Related Order --}}
                    <div class="md:col-span-6">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                            Related Order <span class="text-ink-tertiary font-normal normal-case">(optional)</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="package" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary"
                                style="width:14px;height:14px; left: 10px;"></i>
                            <input type="number" name="order_id" value="{{ old('order_id') }}" placeholder="e.g. 12345"
                                class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-12">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                            Describe the Issue
                        </label>
                        <x-textarea-input name="description" :value="old('description')" required rows="9" maxlength="10000"
                            placeholder="Provide as much detail as possible — order numbers, screenshots of error, what you expected, etc." />
                        @error('description')
                            <div class="text-feedback-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-6 pt-4 border-t border-border flex flex-wrap gap-2 justify-end">
                    <a href="{{ route('seller.support.index') }}" class="btn btn-light">
                        <i data-lucide="x" style="width:14px;height:14px;"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" style="width:14px;height:14px;"></i> Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </section>

@endsection
