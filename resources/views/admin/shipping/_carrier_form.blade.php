@php $c = $carrier ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Carrier Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $c->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">API Endpoint</label>
    <input type="url" name="api_endpoint" class="form-control" value="{{ old('api_endpoint', $c->api_endpoint ?? '') }}" placeholder="https://api.carrier.com/v1/">
</div>
<div class="mb-3">
    <label class="form-label">API Key</label>
    <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $c->api_key ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Logo URL</label>
    <input type="url" name="logo" class="form-control" value="{{ old('logo', $c->logo ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $c->description ?? '') }}</textarea>
</div>
<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="carrierActiveCheck"
        {{ !$c || $c->is_active ? 'checked' : '' }}>
    <label class="form-check-label" for="carrierActiveCheck">Active</label>
</div>
