@php $m = $method ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Method Type --}}
    <div>
        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">
            Method Type
        </label>
        <select name="method_type"
            class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors method-type-select @error('method_type') is-invalid @enderror"
            required>
            <option value="">Select type…</option>
            @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::methodTypes() as $value => $label)
                <option value="{{ $value }}" {{ $m && $m->method_type === $value ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
        @error('method_type')
            <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
        @enderror
    </div>

    {{-- Account Name --}}
    <div>
        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">
            Account Name
        </label>
        <input type="text" name="account_name" value="{{ old('account_name', $m->account_name ?? '') }}"
            placeholder="As shown on your account"
            class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('account_name') is-invalid @enderror"
            required>
        @error('account_name')
            <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
        @enderror
    </div>

    {{-- Account Number --}}
    <div class="md:col-span-2">
        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">
            Account Number
        </label>
        <input type="text" name="account_number" value="{{ old('account_number', $m->account_number ?? '') }}"
            placeholder="Full account number"
            class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors font-mono @error('account_number') is-invalid @enderror"
            required>
        @error('account_number')
            <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
        @enderror
        <small class="text-ink-tertiary mt-1 block">Stored encrypted. Visible only in masked form (e.g.
            ****1234).</small>
    </div>

    {{-- Bank fields --}}
    <div
        class="bank-fields md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 {{ $m && $m->method_type !== 'bank' ? 'd-none' : '' }}">
        <div>
            <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Bank
                Name</label>
            <input type="text" name="bank_name" value="{{ old('bank_name', $m->bank_name ?? '') }}"
                placeholder="e.g. DBBL"
                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
        </div>
        <div>
            <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Branch
                Name</label>
            <input type="text" name="branch_name" value="{{ old('branch_name', $m->branch_name ?? '') }}"
                placeholder="e.g. Dhanmondi"
                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
        </div>
        <div>
            <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Routing
                Number</label>
            <input type="text" name="routing_number" value="{{ old('routing_number', $m->routing_number ?? '') }}"
                placeholder="e.g. 012345678"
                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors font-mono">
        </div>
    </div>

    {{-- Mobile banking fields --}}
    <div class="mobile-fields md:col-span-2 {{ $m && $m->method_type !== 'mobile_banking' ? 'd-none' : '' }}">
        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Mobile
            Provider</label>
        <select name="mobile_provider"
            class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
            <option value="">Select provider…</option>
            @foreach (\App\Domain\Vendor\Models\SellerPayoutMethod::mobileProviders() as $value => $label)
                <option value="{{ $value }}" {{ $m && $m->mobile_provider === $value ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Default switch --}}
    <div class="md:col-span-2">
        <label
            class="flex items-center gap-3 cursor-pointer select-none p-3 rounded-xs bg-surface-muted hover:bg-brand-tint transition-colors"
            for="isDefault{{ $m ? $m->id : 'New' }}">
            <input type="checkbox" name="is_default" value="1"
                class="h-4 w-4 rounded border-border text-brand focus:ring-brand focus:ring-2"
                id="isDefault{{ $m ? $m->id : 'New' }}" {{ $m && $m->is_default ? 'checked' : '' }}>
            <div>
                <p class="mb-0 font-semibold text-ink-emphasis text-sm">Set as default method</p>
                <small class="text-ink-tertiary">Pre-selected for new payout requests.</small>
            </div>
        </label>
    </div>
</div>
