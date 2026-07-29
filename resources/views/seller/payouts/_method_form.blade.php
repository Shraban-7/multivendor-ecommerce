@php $m = $method ?? null; @endphp

<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Method Type </label>
    <select name="method_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors method-type-select" required>
        <option value="">Select type...</option>
        @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::methodTypes() as $value => $label)
            <option value="{{ $value }}" {{ $m && $m->method_type === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Account Name </label>
    <input type="text" name="account_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('account_name', $m->account_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Account Number</label>
    <input type="text" name="account_number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('account_number', $m->account_number ?? '') }}" required>
</div>

<div class="bank-fields mb-3 {{ $m && $m->method_type !== 'bank' ? 'd-none' : '' }}">
    <div class="mb-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Bank Name</label>
        <input type="text" name="bank_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('bank_name', $m->bank_name ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Branch Name</label>
        <input type="text" name="branch_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('branch_name', $m->branch_name ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Routing Number</label>
        <input type="text" name="routing_number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('routing_number', $m->routing_number ?? '') }}">
    </div>
</div>

<div class="mobile-fields mb-3 {{ $m && $m->method_type !== 'mobile_banking' ? 'd-none' : '' }}">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Mobile Provider</label>
    <select name="mobile_provider" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
        <option value="">Select provider...</option>
        @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::mobileProviders() as $value => $label)
            <option value="{{ $value }}" {{ $m && $m->mobile_provider === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_default" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="isDefault{{ $m ? $m->id : 'New' }}"
        {{ $m && $m->is_default ? 'checked' : '' }}>
    <label class="text-sm text-ink" for="isDefault{{ $m ? $m->id : 'New' }}">Set as default method</label>
</div>