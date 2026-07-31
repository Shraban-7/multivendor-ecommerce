@php
    $pageTitle = 'Shop Settings';
    $tabs = [
        ['key' => 'info',    'label' => 'Shop Info',           'icon' => 'info'],
        ['key' => 'address', 'label' => 'Address & Shipping', 'icon' => 'map-pin'],
        ['key' => 'identity','label' => 'Identity & Documents','icon' => 'file-text'],
        ['key' => 'media',   'label' => 'Media',              'icon' => 'image'],
    ];
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="settings" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $pageTitle }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="sliders-horizontal" style="width:11px;height:11px;" class="me-1"></i> Configuration
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage shop details, address, identity documents and media in one place.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ SETTINGS TAB CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    {{-- Tabs nav --}}
    <div class="px-5 pt-3 flex flex-wrap gap-2 border-b border-border" role="tablist">
        @foreach ($tabs as $i => $tab)
            <button class="flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-t-xs transition-colors {{ $i === 0 ? 'text-brand-deep border-b-2 border-brand bg-brand-tint' : 'text-ink-tertiary hover:text-ink-emphasis hover:bg-surface-muted' }}"
                    id="{{ $tab['key'] }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tab['key'] }}"
                    type="button"
                    role="tab">
                <i data-lucide="{{ $tab['icon'] }}" style="width:14px;height:14px;"></i>
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <form id="businessSettingsForm" action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="tab-content p-6" id="settingsTabContent">

            {{-- TAB 1: Shop Info --}}
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business Name <span class="text-feedback-danger">*</span></label>
                        <input type="text" id="business_name" name="business_name"
                               value="{{ old('business_name', $seller->business_name) }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business Email <span class="text-feedback-danger">*</span></label>
                        <input type="email" id="business_email" name="business_email"
                               value="{{ old('business_email', $seller->business_email) }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Shop Type <span class="text-feedback-danger">*</span></label>
                        <select name="shop_type" id="shop_type"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            @foreach (['individual' => 'Individual', 'business' => 'Business', 'company' => 'Company'] as $value => $label)
                                <option value="{{ $value }}" {{ old('shop_type', $seller->shop_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Shop Description</label>
                    <x-textarea-input name="business_description" :value="old('business_description', $seller->business_description)" rows="4" maxlength="5000" />
                    <small class="text-ink-tertiary mt-1 block">Tell customers about your shop (max 5000 characters).</small>
                </div>
                <div class="mt-6 flex justify-end pt-4 border-t border-border">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:16px;height:16px;"></i> Save Changes
                    </button>
                </div>
            </div>

            {{-- TAB 2: Address & Shipping --}}
            <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business Address</label>
                        <textarea name="business_address" id="business_address" rows="2"
                                  class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">{{ old('business_address', $seller->business_address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Division</label>
                        <select name="division_id" id="divisionSelect"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" {{ (string) old('division_id', $seller->division_id) === (string) $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">District</label>
                        <select name="district_id" id="districtSelect"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Default Shipping Cost</label>
                        <input type="number" id="shipping_cost" name="shipping_cost"
                               value="{{ old('shipping_cost', $seller->shipping_cost) }}" step="0.01"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        <small class="text-ink-tertiary mt-1 block">Applied to your products unless overridden per product.</small>
                    </div>
                </div>
                <div class="mt-6 flex justify-end pt-4 border-t border-border">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:16px;height:16px;"></i> Save Changes
                    </button>
                </div>
            </div>

            {{-- TAB 3: Identity & Documents --}}
            <div class="tab-pane fade" id="identity" role="tabpanel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">NID Number</label>
                        <input type="text" name="nid_no"
                               value="{{ old('nid_no', $seller->nid_no) }}"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Trade License No</label>
                        <input type="text" name="trade_license_no"
                               value="{{ old('trade_license_no', $seller->trade_license_no) }}"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">NID Front</label>
                        <x-image-input name="nid_front_image"
                                       :image="auth('seller')->user()->nid_front_image
                                            ? storage_url(auth('seller')->user()->nid_front_image)
                                            : asset('assets/frontend/images/default.png')" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">NID Back</label>
                        <x-image-input name="nid_back_image"
                                       :image="auth('seller')->user()->nid_back_image
                                            ? storage_url(auth('seller')->user()->nid_back_image)
                                            : asset('assets/frontend/images/default.png')" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Trade License</label>
                        <x-image-input name="trade_license_image"
                                       :image="auth('seller')->user()->trade_license_image
                                            ? storage_url(auth('seller')->user()->trade_license_image)
                                            : asset('assets/frontend/images/default.png')" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end pt-4 border-t border-border">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:16px;height:16px;"></i> Save Changes
                    </button>
                </div>
            </div>

            {{-- TAB 4: Media --}}
            <div class="tab-pane fade" id="media" role="tabpanel">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Business Logo</label>
                        <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                        <small class="text-ink-tertiary mt-1 block">Used across the marketplace storefront and admin listings.</small>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Cover Photo</label>
                        <x-image-input name="cover_image" :image="storage_url($seller->cover_image)" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Shop Image</label>
                        <x-image-input name="shop_image" :image="storage_url($seller->shop_image)" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end pt-4 border-t border-border">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:16px;height:16px;"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts')
<script>
    (function () {
        let sellerDivision = @json((string) ($seller->division_id ?? ''));
        let sellerDistrict = @json((string) ($seller->district_id ?? ''));
        let districtUrl     = @json(url('/get-districts'));

        function loadDistricts(divisionId, selectedDistrict = null) {
            const $dist = $('#districtSelect');
            $dist.html('<option value="">Loading...</option>');

            if (!divisionId) {
                $dist.html('<option value="">Select District</option>');
                return;
            }
            $.get(districtUrl + '/' + divisionId, function (data) {
                let opts = '<option value="">Select District</option>';
                $.each(data, function (key, label) {
                    const selected = String(selectedDistrict) === String(key) ? 'selected' : '';
                    opts += `<option value="${key}" ${selected}>${label}</option>`;
                });
                $dist.html(opts);
            });
        }

        if (sellerDivision) {
            loadDistricts(sellerDivision, sellerDistrict);
        }
        $('#divisionSelect').on('change', function () {
            loadDistricts($(this).val());
        });

        const $form = $('#businessSettingsForm');
        const $submitBtn = $form.find("button[type='submit']");

        $form.on('submit', function (e) {
            e.preventDefault();
            const originalHtml = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('<i data-lucide="loader-circle" class="animate-spin" style="width:16px;height:16px;"></i> Saving...');
            window.renderIcons && window.renderIcons();

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (res) {
                    $submitBtn.prop('disabled', false).html(originalHtml);
                    window.renderIcons && window.renderIcons();
                    showSuccessToast((res && res.message) || 'Settings saved successfully');
                },
                error: function (xhr) {
                    $submitBtn.prop('disabled', false).html(originalHtml);
                    window.renderIcons && window.renderIcons();
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        const msgs = [];
                        $.each(errors, function (field, arr) {
                            const label = field.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                            msgs.push('<strong>' + label + ':</strong> ' + arr.join(', '));
                        });
                        showErrorToast(msgs.join('<br>'));
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showErrorToast(xhr.responseJSON.message);
                    } else {
                        showErrorToast('Could not save settings. Please try again.');
                    }
                }
            });
        });
    })();
</script>
@endpush

@endsection
