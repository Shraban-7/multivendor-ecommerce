@extends('admin.layouts.app')
@section('title', 'Edit Payment Gateway')

@section('content')
    <h5 class="mb-3">Edit Payment Gateway</h5>
    <div class="row col-md-6">
        <div class="card card-body">
            <form action="{{ route('admin.payment_gateways.update', $paymentGateway->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Gateway Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $paymentGateway->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment URL</label>
                    <input type="text" name="payment_url" class="form-control" value="{{ old('payment_url', $paymentGateway->payment_url) }}" required>
                </div>

                <div class="bg-light p-3 mb-3">
                    <h5>API Credentials</h5>
                    <div id="credentials-container">
                        @foreach ($paymentGateway->credentials ?? [] as $key => $value)
                            <div class="row mb-2 credential-row">
                                <div class="col">
                                    <input type="text" name="credentials_keys[]" class="form-control" value="{{ $key }}" placeholder="Key (e.g. store_id)">
                                </div>
                                <div class="col">
                                    <input type="text" name="credentials_values[]" class="form-control" value="{{ $value }}" placeholder="Value">
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
                    <label class="form-label">Is Default?</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="is_default" value="1" class="form-check-input" id="default_yes"
                            {{ $paymentGateway->is_default ? 'checked' : '' }}>
                        <label class="form-check-label" for="default_yes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="is_default" value="0" class="form-check-input" id="default_no"
                            {{ !$paymentGateway->is_default ? 'checked' : '' }}>
                        <label class="form-check-label" for="default_no">No</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="is_enabled" value="1" class="form-check-input" id="enabled"
                            {{ $paymentGateway->is_enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">Enabled</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="is_enabled" value="0" class="form-check-input" id="disabled"
                            {{ !$paymentGateway->is_enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="disabled">Disabled</label>
                    </div>
                </div>

                <div class="mb-3 col-12">
                    <label class="form-label">Image</label>
                    <x-image-input name="image" :image="storage_url($paymentGateway->image)" />
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
                        <div class="row mb-2 credential-row">
                            <div class="col">
                                <input type="text" name="credentials_keys[]" class="form-control" placeholder="Key (e.g. store_id)">
                            </div>
                            <div class="col">
                                <input type="text" name="credentials_values[]" class="form-control" placeholder="Value">
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
                        alert('At least one credential row is required.');
                    }
                });
            });
        </script>
    @endpush
@endsection
