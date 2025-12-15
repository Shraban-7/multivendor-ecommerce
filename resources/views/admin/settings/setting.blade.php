@extends('admin.layouts.app')
@section('title', 'General Settings')
@section('content')

<h4 class="mb-4">General Settings</h4>

<div class="row">
    <div class="col-12 col-xl-10">
        <div class="card shadow-sm">
            <div class="card-body p-4">
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
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Basic Application Info</h5>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="app_name" class="form-label">Application Name</label>
                                    <input type="text" id="app_name" name="app_name" class="form-control" value="{{ old('app_name', $setting->app_name) }}" placeholder="e.g., My Awesome App">
                                </div>

                                <div class="col-12">
                                    <hr class="my-3">
                                </div>

                                <div class="col-12 mb-4">
                                    <h5 class="mb-3">Branding and Icons</h5>
                                    <div class="row">
                                        <div class="col-12 col-md-4 mb-4">
                                            <label class="form-label">Main Logo</label>
                                            <x-image-input name="logo" :image="storage_url($setting->logo)" />
                                        </div>

                                        <div class="col-12 col-md-4 mb-4">
                                            <label class="form-label">White/Light Logo (for dark backgrounds)</label>
                                            <x-image-input name="logo_white" :image="storage_url($setting->logo_white)" />
                                        </div>

                                        <div class="col-12 col-md-4 mb-4">
                                            <label class="form-label">Favicon (Small browser tab icon)</label>
                                            @if ($setting->favicon)
                                            <div class="mb-2">
                                                <img src="{{ asset($setting->favicon) }}" alt="Current Favicon" width="32" height="32" class="border p-1 rounded">
                                            </div>
                                            @endif
                                            <input type="file" name="favicon" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">SEO Meta Tags</h5>
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="seo_title" class="form-label">SEO Title</label>
                                    <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title', $setting->seo_title) }}" placeholder="e.g., Best Service - Your App Name">
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="seo_description" class="form-label">SEO Description</label>
                                    <textarea id="seo_description" name="seo_description" class="form-control" rows="3" placeholder="A concise description of your website content for search engines.">{{ old('seo_description', $setting->seo_description) }}</textarea>
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="seo_keywords" class="form-label">SEO Keywords (Comma Separated)</label>
                                    <input type="text" id="seo_keywords" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $setting->seo_keywords) }}" placeholder="e.g., service, product, marketing">
                                </div>

                                <div class="col-12">
                                    <hr class="my-3">
                                </div>

                                <div class="col-12">
                                    <h5 class="mb-3">Analytics & Tracking</h5>
                                    <p class="text-muted small">Enter the IDs or necessary tracking codes for third-party services.</p>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="google_tag_manager" class="form-label">Google Tag Manager ID</label>
                                    <input type="text" id="google_tag_manager" name="google_tag_manager" class="form-control" value="{{ old('google_tag_manager', $setting->google_tag_manager) }}" placeholder="GTM-XXXXXXX">
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="google_analytics" class="form-label">Google Analytics Measurement ID</label>
                                    <input type="text" id="google_analytics" name="google_analytics" class="form-control" value="{{ old('google_analytics', $setting->google_analytics) }}" placeholder="G-XXXXXXXXXX">
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="facebook_pixel" class="form-label">Facebook Pixel ID</label>
                                    <input type="text" id="facebook_pixel" name="facebook_pixel" class="form-control" value="{{ old('facebook_pixel', $setting->facebook_pixel) }}" placeholder="1234567890">
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="facebook_capi" class="form-label">Facebook CAPI Access Token</label>
                                    <input type="text" id="facebook_capi" name="facebook_capi" class="form-control" value="{{ old('facebook_capi', $setting->facebook_capi) }}" placeholder="EAA... (Token)">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Contact Information</h5>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $setting->email) }}" placeholder="contact@example.com">
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $setting->phone) }}" placeholder="+1 (555) 123-4567">
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="address" class="form-label">Physical Address</label>
                                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter your full business address here.">{{ old('address', $setting->address) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <hr class="my-3">
                                </div>

                                <div class="col-12 mb-4">
                                    <h5 class="mb-3">Footer Customization</h5>
                                    <label for="footer_text" class="form-label">Footer Copyright/Text</label>
                                    <textarea id="footer_text" name="footer_text" class="form-control" rows="2" placeholder="e.g., Copyright © {{ date('Y') }} All Rights Reserved.">{{ old('footer_text', $setting->footer_text) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4 pt-3 border-top">
                        <button type="submit" id="updateBtn" class="btn btn-primary shadow-sm">
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