@php $c = $carrier ?? null; @endphp

<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Carrier Name </label>
    <input type="text" name="name"
        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
        value="{{ old('name', $c->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">API Endpoint</label>
    <input type="url" name="api_endpoint"
        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
        value="{{ old('api_endpoint', $c->api_endpoint ?? '') }}" placeholder="https://api.carrier.com/v1/">
</div>
<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">API Key</label>
    <input type="text" name="api_key"
        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
        value="{{ old('api_key', $c->api_key ?? '') }}">
</div>
<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Logo URL</label>
    <input type="url" name="logo"
        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
        value="{{ old('logo', $c->logo ?? '') }}">
</div>
<div class="mb-3">
    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
    <x-textarea-input name="description" :value="old('description', $c->description ?? '')" />
</div>
<div class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1"
        class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="carrierActiveCheck"
        {{ !$c || $c->is_active ? 'checked' : '' }}>
    <label class="text-sm text-ink" for="carrierActiveCheck">Active</label>
</div>
