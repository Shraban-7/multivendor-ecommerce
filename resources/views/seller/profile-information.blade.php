@extends('seller.layouts.app')
@section('title', 'Edit Profile')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="user-cog" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Edit Profile</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Edit Profile</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="user" style="width:11px;height:11px;" class="me-1"></i> Account
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Update your personal details and account password.</p>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

<section class="grid grid-cols-1 md:grid-cols-2 gap-3">
    {{-- Personal Info --}}
    <form id="personalForm" class="flex flex-col">
        @csrf
        <input type="hidden" name="section" value="personal">

        <div class="bg-white rounded-sm shadow-sm overflow-hidden flex-1 flex flex-col">
            <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                <i data-lucide="user" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Personal Information</h3>
            </div>
            <div class="p-5 grow">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Full Name <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="name" value="{{ auth('seller')->user()->name }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Email <span class="text-feedback-danger">*</span></label>
                        <input type="email" name="email" value="{{ auth('seller')->user()->email }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Phone <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="phone" value="{{ auth('seller')->user()->phone }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Profile Picture</label>
                        <x-image-input name="image" :image="auth('seller')->user()->image
                            ? storage_url(auth('seller')->user()->image)
                            : asset('assets/frontend/images/default.png')" />
                    </div>
                </div>
            </div>
            <div class="text-right p-4 border-t border-border">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" style="width:14px;height:14px;"></i> Update Personal Info
                </button>
            </div>
        </div>
    </form>

    {{-- Password --}}
    <form id="passwordForm" class="flex flex-col">
        @csrf
        <input type="hidden" name="section" value="password">

        <div class="bg-white rounded-sm shadow-sm overflow-hidden flex-1 flex flex-col">
            <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                <i data-lucide="lock" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Update Password</h3>
            </div>
            <div class="p-5 grow">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Current Password</label>
                        <input type="password" name="current_password"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">New Password</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                </div>
            </div>
            <div class="text-right p-4 border-t border-border">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="key" style="width:14px;height:14px;"></i> Update Password
                </button>
            </div>
        </div>
    </form>
</section>

@push('scripts')
    <script>
        $(function() {
            $('#personalForm, #passwordForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);
                let btn = form.find('button[type=submit]');

                $.ajax({
                    url: "{{ route('seller.profile') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        btn.prop('disabled', true).html(
                            '<i data-lucide="loader-circle" style="width:14px;height:14px;" class="animate-spin me-1"></i> Saving...');
                        if (window.renderIcons) { window.renderIcons(); }
                    },
                    success: function(res) {
                        btn.prop('disabled', false).html('<i data-lucide="check" style="width:14px;height:14px;"></i> Saved');
                        if (window.renderIcons) { window.renderIcons(); }
                        showSuccessToast(res.message);

                        if (form.find('input[name="section"]').val() === 'password') {
                            form[0].reset();
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Save');
                        if (window.renderIcons) { window.renderIcons(); }

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let message = Object.values(errors)
                                .map(err => err[0])
                                .join('<br>');
                            showErrorToast(message);
                        } else {
                            showErrorToast('Something went wrong!');
                        }
                    }
                });
            });
        });
    </script>
@endpush

@endsection
