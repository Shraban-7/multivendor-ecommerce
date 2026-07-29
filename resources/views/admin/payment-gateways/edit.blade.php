@extends('admin.layouts.app')
@section('title', 'Edit Payment Gateway')

@section('content')
    <h5 class="mb-3">Edit Payment Gateway</h5>
    <div class="grid grid-cols-1 md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
            <form action="{{ route('admin.paymentGateways.update', $gateway->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Gateway Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('name', $gateway->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Payment URL</label>
                    <input type="text" name="payment_url" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('payment_url', $gateway->payment_url) }}" required>
                </div>

                <div class="bg-surface-muted p-3 mb-3">
                    <h5>API Credentials</h5>
                    <div id="credentials-container">
                        @foreach ($gateway->credentials ?? [] as $key => $value)
                            <div class="grid grid-cols-1 mb-2 credential-row">
                                <div class="col">
                                    <input type="text" name="credentials_keys[]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $key }}" placeholder="Key (e.g. store_id)">
                                </div>
                                <div class="col">
                                    <input type="text" name="credentials_values[]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $value }}" placeholder="Value">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger remove-credential">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <button type="button" id="add-credential" class="btn btn-primary btn-sm">+ Add Credential</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Is Default?</label><br>
                    <div class="flex items-center gap-2 flex items-center gap-2-inline">
                        <input type="radio" name="is_default" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="default_yes"
                            {{ $gateway->is_default ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="default_yes">Yes</label>
                    </div>
                    <div class="flex items-center gap-2 flex items-center gap-2-inline">
                        <input type="radio" name="is_default" value="0" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="default_no"
                            {{ !$gateway->is_default ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="default_no">No</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label><br>
                    <div class="flex items-center gap-2 flex items-center gap-2-inline">
                        <input type="radio" name="is_enabled" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="enabled"
                            {{ $gateway->is_enabled ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="enabled">Enabled</label>
                    </div>
                    <div class="flex items-center gap-2 flex items-center gap-2-inline">
                        <input type="radio" name="is_enabled" value="0" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="disabled"
                            {{ !$gateway->is_enabled ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="disabled">Disabled</label>
                    </div>
                </div>

                <div class="mb-3 col-span-full">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                    <x-image-input name="image" :image="storage_url($gateway->image)" />
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#add-credential').on('click', function() {
                    const row = `
                        <div class="grid grid-cols-1 mb-2 credential-row">
                            <div class="col">
                                <input type="text" name="credentials_keys[]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Key (e.g. store_id)">
                            </div>
                            <div class="col">
                                <input type="text" name="credentials_values[]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Value">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger remove-credential">&times;</button>
                            </div>
                        </div>`;
                    $('#credentials-container').append(row);
                });

                $(document).on('click', '.remove-credential', function() {
                    const totalRows = $('.credential-row').length;
                    if (totalRows > 1) {
                        $(this).closest('.credential-row').remove();
                    } else {
                        alert('At least one credential grid grid-cols-1 is required.');
                    }
                });
            });
        </script>
    @endpush
@endsection
