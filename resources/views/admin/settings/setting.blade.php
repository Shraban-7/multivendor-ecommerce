@extends('admin.layouts.app')
@section('title', 'General Settings')
@section('content')

<h4 class="mb-4">General Settings</h4>

<div class="grid grid-cols-1">
    <div class="col-span-full xl:col-span-1-10">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow-sm">
            <div class="p-5 p-4">
                <form id="settingsForm" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">SEO & Tracking</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact & Footer</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="settingsTabsContent">

                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="grid grid-cols-1">
                                <div class="col-span-full">
                                    <h5 class="mb-3">Basic Application Info</h5>
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="app_name" class="block text-xs font-medium text-ink-secondary mb-1">Application Name</label>
                                    <input type="text" id="app_name" name="app_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('app_name', $setting->app_name) }}" placeholder="e.g., My Awesome App">
                                </div>

                                <div class="col-span-full">
                                    <hr class="my-3">
                                </div>

                                <div class="col-span-full mb-4">
                                    <h5 class="mb-3">Branding and Icons</h5>
                                    <div class="grid grid-cols-1">
                                        <div class="col-span-full md:col-span-1 mb-4">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1">Main Logo</label>
                                            <x-image-input name="logo" :image="storage_url($setting->logo)" />
                                        </div>

                                        <div class="col-span-full md:col-span-1 mb-4">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1">White/Light Logo (for dark backgrounds)</label>
                                            <x-image-input name="logo_white" :image="storage_url($setting->logo_white)" />
                                        </div>

                                        <div class="col-span-full md:col-span-1 mb-4">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1">Favicon (Small browser tab icon)</label>
                                            @if ($setting->favicon)
                                            <div class="mb-2">
                                                <img src="{{ asset($setting->favicon) }}" alt="Current Favicon" width="32" height="32" class="border p-1 rounded">
                                            </div>
                                            @endif
                                            <input type="file" name="favicon" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                            <div class="grid grid-cols-1">
                                <div class="col-span-full">
                                    <h5 class="mb-3">SEO Meta Tags</h5>
                                </div>

                                <div class="col-span-full mb-4">
                                    <label for="seo_title" class="block text-xs font-medium text-ink-secondary mb-1">SEO Title</label>
                                    <input type="text" id="seo_title" name="seo_title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('seo_title', $setting->seo_title) }}" placeholder="e.g., Best Service - Your App Name">
                                </div>

                                <div class="col-span-full mb-4">
                                    <label for="seo_description" class="block text-xs font-medium text-ink-secondary mb-1">SEO Description</label>
                                    <textarea id="seo_description" name="seo_description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="A concise description of your website content for search engines.">{{ old('seo_description', $setting->seo_description) }}</textarea>
                                </div>

                                <div class="col-span-full mb-4">
                                    <label for="seo_keywords" class="block text-xs font-medium text-ink-secondary mb-1">SEO Keywords (Comma Separated)</label>
                                    <input type="text" id="seo_keywords" name="seo_keywords" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('seo_keywords', $setting->seo_keywords) }}" placeholder="e.g., service, product, marketing">
                                </div>

                                <div class="col-span-full">
                                    <hr class="my-3">
                                </div>

                                <div class="col-span-full">
                                    <h5 class="mb-3">Analytics & Tracking</h5>
                                    <p class="text-ink-tertiary text-sm">Enter the IDs or necessary tracking codes for third-party services.</p>
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="google_tag_manager" class="block text-xs font-medium text-ink-secondary mb-1">Google Tag Manager ID</label>
                                    <input type="text" id="google_tag_manager" name="google_tag_manager" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('google_tag_manager', $setting->google_tag_manager) }}" placeholder="GTM-XXXXXXX">
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="google_analytics" class="block text-xs font-medium text-ink-secondary mb-1">Google Analytics Measurement ID</label>
                                    <input type="text" id="google_analytics" name="google_analytics" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('google_analytics', $setting->google_analytics) }}" placeholder="G-XXXXXXXXXX">
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="facebook_pixel" class="block text-xs font-medium text-ink-secondary mb-1">Facebook Pixel ID</label>
                                    <input type="text" id="facebook_pixel" name="facebook_pixel" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('facebook_pixel', $setting->facebook_pixel) }}" placeholder="1234567890">
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="facebook_capi" class="block text-xs font-medium text-ink-secondary mb-1">Facebook CAPI Access Token</label>
                                    <input type="text" id="facebook_capi" name="facebook_capi" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('facebook_capi', $setting->facebook_capi) }}" placeholder="EAA... (Token)">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="grid grid-cols-1">
                                <div class="col-span-full">
                                    <h5 class="mb-3">Contact Information</h5>
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="email" class="block text-xs font-medium text-ink-secondary mb-1">Email Address</label>
                                    <input type="email" id="email" name="email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('email', $setting->email) }}" placeholder="contact@example.com">
                                </div>

                                <div class="col-span-full md:col-span-1 mb-4">
                                    <label for="phone" class="block text-xs font-medium text-ink-secondary mb-1">Phone Number</label>
                                    <input type="text" id="phone" name="phone" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('phone', $setting->phone) }}" placeholder="+1 (555) 123-4567">
                                </div>

                                <div class="col-span-full mb-4">
                                    <label for="address" class="block text-xs font-medium text-ink-secondary mb-1">Physical Address</label>
                                    <textarea id="address" name="address" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Enter your full business address here.">{{ old('address', $setting->address) }}</textarea>
                                </div>

                                <div class="col-span-full">
                                    <hr class="my-3">
                                </div>

                                <div class="col-span-full mb-4">
                                    <h5 class="mb-3">Footer Customization</h5>
                                    <label for="footer_text" class="block text-xs font-medium text-ink-secondary mb-1">Footer Copyright/Text</label>
                                    <textarea id="footer_text" name="footer_text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2" placeholder="e.g., Copyright © {{ date('Y') }} All Rights Reserved.">{{ old('footer_text', $setting->footer_text) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4 pt-3 border-t">
                        <button type="submit" id="updateBtn" class="btn btn-primary">
                            Update All Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap Tabs (if using Bootstrap 5, otherwise adjust based on your version)
        var triggerTabList = [].slice.call(document.querySelectorAll('#settingsTabs button'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    });
</script>
@endpush