@extends('seller.layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <div class="w-full px-0">
        <h4 class="font-bold mb-3 text-ink">Edit Profile</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
            <div class="md:col-span-1 flex">
                <form id="personalForm" class="flex-1 flex flex-col">
                    @csrf
                    <input type="hidden" name="section" value="personal">

                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1 flex flex-col" style="border-radius: 12px;">
                        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                            <h5 class="font-semibold text-ink mb-0">
                                Personal Information
                            </h5>
                        </div>
                        <div class="p-5 grow">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Full Name</label>
                                    <input type="text" name="name" value="{{ auth('seller')->user()->name }}"
                                        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                                </div>

                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Email</label>
                                    <input type="email" name="email" value="{{ auth('seller')->user()->email }}"
                                        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                                </div>

                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Phone</label>
                                    <input type="text" name="phone" value="{{ auth('seller')->user()->phone }}"
                                        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                                </div>

                                <div class="col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Profile Picture</label>
                                    <x-image-input name="image" :image="auth('seller')->user()->image
                                        ? storage_url(auth('seller')->user()->image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>
                            </div>
                        </div>
                        <div class="text-right p-3 border-t bg-white">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Update Personal Info</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="md:col-span-1 flex">
                <form id="passwordForm" class="flex-1 flex flex-col">
                    @csrf
                    <input type="hidden" name="section" value="password">

                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1 flex flex-col" style="border-radius: 12px;">
                        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                            <h5 class="font-semibold text-ink mb-0">Update Password</h5>
                        </div>

                        <div class="p-5 grow">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Current Password</label>
                                    <input type="password" name="current_password" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>

                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">New Password</label>
                                    <input type="password" name="password" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>

                                <div class="md:col-span-full">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                            </div>
                        </div>

                        <div class="text-right p-3 border-t bg-white">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('form').on('submit', function(e) {
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
                                '<i class="fa fa-spinner fa-spin"></i> Saving...');
                        },
                        success: function(res) {
                            btn.prop('disabled', false).html('Saved');
                            showSuccessToast(res.message);

                            if (form.find('input[name="section"]').val() === 'password') {
                                form[0].reset();
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('Save');

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
