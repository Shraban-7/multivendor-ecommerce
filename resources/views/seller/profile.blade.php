@extends('seller.layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Edit Profile</h4>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5" style="border-radius: 12px;">
                <form id="profileForm" action="{{ route('seller.profile', $seller->username) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-full mb-3">
                            <label for="name" class="block text-xs font-medium text-ink-secondary mb-1">Full Name</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="name" name="name"
                                value="{{ old('name', $seller->name) }}" required>
                        </div>
                        <div class="md:col-span-1 mb-3">
                            <label for="email" class="block text-xs font-medium text-ink-secondary mb-1">Email</label>
                            <input type="email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="email" name="email"
                                value="{{ old('email', $seller->email) }}" required>
                        </div>
                        <div class="md:col-span-1 mb-3">
                            <label for="phone" class="block text-xs font-medium text-ink-secondary mb-1">Phone Number</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="phone" name="phone"
                                value="{{ old('phone', $seller->phone) }}" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-1 mb-3">
                            <label for="business_name" class="block text-xs font-medium text-ink-secondary mb-1">Business Name</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="business_name" name="business_name"
                                value="{{ old('business_name', $seller->business_name) }}" required>
                        </div>
                        <div class="md:col-span-1 mb-3">
                            <label for="business_email" class="block text-xs font-medium text-ink-secondary mb-1">Business Email</label>
                            <input type="email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="business_email" name="business_email"
                                value="{{ old('business_email', $seller->business_email) }}" required>
                        </div>
                        <div class="md:col-span-full mb-3">
                            <label for="business_email" class="block text-xs font-medium text-ink-secondary mb-1">Business Address</label>
                            <textarea name="business_address" id="business_address" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"  cols="30" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="col-span-1 mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Profile Picture</label>
                            <x-image-input name="image" :image="storage_url($seller->image)"/>
                        </div>
                        <div class="col-span-1 mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)"/>
                        </div>
                    </div>

                   <button type="submit" id="submitBtn" class="btn-theme inline-flex items-center gap-1">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')

    @endpush
@endsection
