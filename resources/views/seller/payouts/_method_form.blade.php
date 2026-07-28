@php $m = $method ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Method Type <span class="text-danger">*</span></label>
    <select name="method_type" class="form-select method-type-select" required>
        <option value="">Select type...</option>
        @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::methodTypes() as $value => $label)
            <option value="{{ $value }}" {{ $m && $m->method_type === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Account Name <span class="text-danger">*</span></label>
    <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $m->account_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Account Number <span class="text-danger">*</span></label>
    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $m->account_number ?? '') }}" required>
</div>

<div class="bank-fields mb-3 {{ $m && $m->method_type !== 'bank' ? 'd-none' : '' }}">
    <div class="mb-3">
        <label class="form-label">Bank Name</label>
        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $m->bank_name ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Branch Name</label>
        <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $m->branch_name ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Routing Number</label>
        <input type="text" name="routing_number" class="form-control" value="{{ old('routing_number', $m->routing_number ?? '') }}">
    </div>
</div>

<div class="mobile-fields mb-3 {{ $m && $m->method_type !== 'mobile_banking' ? 'd-none' : '' }}">
    <label class="form-label">Mobile Provider</label>
    <select name="mobile_provider" class="form-select">
        <option value="">Select provider...</option>
        @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::mobileProviders() as $value => $label)
            <option value="{{ $value }}" {{ $m && $m->mobile_provider === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="form-check">
    <input type="checkbox" name="is_default" value="1" class="form-check-input" id="isDefault{{ $m ? $m->id : 'New' }}"
        {{ $m && $m->is_default ? 'checked' : '' }}>
    <label class="form-check-label" for="isDefault{{ $m ? $m->id : 'New' }}">Set as default method</label>
</div>
