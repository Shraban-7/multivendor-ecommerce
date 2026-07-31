@php
    $counts = $counts ?? ['total' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Social Links')

@section('content')
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="at-sign" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Settings</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Social Links</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Social Links</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#3b82f6]/15 text-[#3b82f6]">
                        <i data-lucide="users" style="width:11px;height:11px;" class="me-1"></i> Reach
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Configure the social media icons customers see throughout the site.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Link
            </button>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="at-sign" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Configured Links <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider ms-2">{{ number_format(counts: $counts['total'] ?? 0) }} total</span></h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Icon</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Link</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($socialLinks as $socialLink)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $socialLink->name }}</td>
                        <td class="px-4 py-3 text-xs">
                            <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $socialLink->icon_name }}</code>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <a href="{{ $socialLink->link }}" target="_blank" class="text-brand-deep hover:underline inline-flex items-center gap-1">
                                {{ Str::limit($socialLink->link, 50) }}
                                <i data-lucide="external-link" style="width:11px;height:11px;"></i>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $socialLink->id }}">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="py-10 text-center">
                                <i data-lucide="at-sign" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No social links yet</p>
                                <p class="text-ink-tertiary text-xs">Add your first social icon.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@include('admin.settings.social_links._form_partial', ['action' => route('admin.settings.social_links.store'), 'method' => 'POST', 'socialLink' => null, 'modalId' => 'addModal', 'modalTitle' => 'Add Social Link'])
@foreach ($socialLinks as $socialLink)
    @include('admin.settings.social_links._form_partial', ['action' => route('admin.settings.social_links.update', $socialLink), 'method' => 'PUT', 'socialLink' => $socialLink, 'modalId' => 'editModal'.$socialLink->id, 'modalTitle' => 'Edit '.$socialLink->name])
@endforeach

@endsection
