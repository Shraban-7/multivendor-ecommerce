@extends('seller.layouts.app')
@section('title', 'Edit Profile')

@section('content')

    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);">
        </div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="user-cog" class="text-feedback-info" style="width:12px;height:12px;"></i>
                        <span>Workspace</span>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Profile</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Edit Profile</h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                            <i data-lucide="store" style="width:11px;height:11px;" class="me-1"></i> Shop Account
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Update your personal info and shop branding details.</p>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div
            class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">
            {{ session('success') }}</div>
    @endif

    <section class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2">
            <form id="profileForm" action="{{ route('seller.profile', $seller->username) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="bg-white rounded-sm shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                        <i data-lucide="user" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Personal Information</h3>
                    </div>
                    <div class="p-5 border-t border-border">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="md:col-span-full">
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Full
                                    Name </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $seller->name) }}"
                                    required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Email
                                </label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $seller->email) }}" required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Phone
                                </label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $seller->phone) }}" required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 border-t border-border">
                        <i data-lucide="store" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Shop Branding</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business
                                    Name </label>
                                <input type="text" id="business_name" name="business_name"
                                    value="{{ old('business_name', $seller->business_name) }}" required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business
                                    Email </label>
                                <input type="email" id="business_email" name="business_email"
                                    value="{{ old('business_email', $seller->business_email) }}" required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business
                                Address</label>
                            <textarea name="business_address" id="business_address" rows="2"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Profile
                                    Picture</label>
                                <x-image-input name="image" :image="storage_url($seller->image)" />
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business
                                    Logo</label>
                                <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-border flex justify-end">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i data-lucide="save" style="width:14px;height:14px;"></i> Update Profile
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <aside class="md:col-span-1">
            <div class="bg-white rounded-sm shadow-sm overflow-hidden h-full">
                <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                    <i data-lucide="user-circle" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                    <h3 class="text-sm font-bold text-ink-emphasis mb-0">Account Snapshot</h3>
                </div>
                <div class="p-5 border-t border-border flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ storage_url($seller->image) }}" alt="{{ $seller->name }}"
                            style="width:64px;height:64px;object-fit:cover;border-radius:12px;"
                            class="shrink-0 bg-surface-muted">
                        <div>
                            <div class="font-bold text-ink-emphasis">{{ $seller->name }}</div>
                            <small class="text-ink-tertiary">@<span
                                    class="font-semibold">{{ $seller->username }}</span></small>
                        </div>
                    </div>
                    <hr class="border-border">
                    <div class="text-sm space-y-1.5">
                        <div class="flex justify-between"><span class="text-ink-tertiary">Email</span><span
                                class="text-ink-emphasis font-medium">{{ $seller->email ?? '—' }}</span></div>
                        <div class="flex justify-between"><span class="text-ink-tertiary">Phone</span><span
                                class="text-ink-emphasis font-medium">{{ $seller->phone ?? '—' }}</span></div>
                        <div class="flex justify-between"><span class="text-ink-tertiary">Joined</span><span
                                class="text-ink-emphasis font-medium">{{ $seller->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <hr class="border-border">
                    <a href="{{ route('seller.profile-information') }}" class="btn btn-light w-full">
                        <i data-lucide="user" style="width:14px;height:14px;"></i> Edit Login Credentials
                    </a>
                </div>
            </div>
        </aside>
    </section>

@endsection
