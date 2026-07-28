@extends('seller.layouts.app')
@section('title', 'Payout Methods')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('seller.payouts.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
            </a>
            <h4 class="fw-bold mb-0 text-dark">Payout Methods</h4>
        </div>
        <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addMethodModal">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Add Method
        </button>
    </div>

    @if ($methods->count() > 0)
        <div class="row g-3">
            @foreach ($methods as $method)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($method->method_type === 'bank')
                                        <div class="icon-bg-primary">
                                            <i data-feather="building" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @elseif ($method->method_type === 'mobile_banking')
                                        <div class="icon-bg-success">
                                            <i data-feather="smartphone" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @else
                                        <div class="icon-bg-warning">
                                            <i data-feather="dollar-sign" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $method->methodLabel() }}</h6>
                                        @if ($method->is_default)
                                            <span class="badge-soft-success" style="font-size: 0.7rem;">Default</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light border btn-sm" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editMethodModal-{{ $method->id }}">
                                                <i data-feather="edit" style="width: 14px; height: 14px;" class="me-2"></i> Edit
                                            </button>
                                        </li>
                                        @if (!$method->is_default)
                                            <li>
                                                <form method="POST" action="{{ route('seller.payouts.methods.default', $method) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">
                                                        <i data-feather="star" style="width: 14px; height: 14px;" class="me-2"></i> Set as Default
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('seller.payouts.methods.destroy', $method) }}"
                                                  onsubmit="return confirm('Delete this payout method?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;" class="me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Account Name:</span>
                                    <span class="fw-medium">{{ $method->account_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Account Number:</span>
                                    <span class="fw-medium">{{ $method->maskedAccountNumber() }}</span>
                                </div>
                                @if ($method->bank_name)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Bank:</span>
                                        <span class="fw-medium">{{ $method->bank_name }}</span>
                                    </div>
                                @endif
                                @if ($method->mobile_provider)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Provider:</span>
                                        <span class="fw-medium">{{ ucfirst($method->mobile_provider) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editMethodModal-{{ $method->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('seller.payouts.methods.update', $method) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Payout Method</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('seller.payouts._method_form', ['method' => $method])
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center py-5">
                <i data-feather="credit-card" style="width: 64px; height: 64px;" class="text-muted mb-3"></i>
                <h5 class="fw-semibold mb-2">No Payout Methods</h5>
                <p class="text-muted mb-3">Add a payout method to start withdrawing your earnings.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                    <i data-feather="plus" class="me-1" style="width: 16px; height: 16px;"></i> Add Payout Method
                </button>
            </div>
        </div>
    @endif
</div>

{{-- Add Method Modal --}}
<div class="modal fade" id="addMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('seller.payouts.methods.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Payout Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('seller.payouts._method_form', ['method' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Method</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    function toggleFields() {
        $('.method-type-select').each(function() {
            let type = $(this).val();
            let form = $(this).closest('.modal-body');
            form.find('.bank-fields').toggleClass('d-none', type !== 'bank');
            form.find('.mobile-fields').toggleClass('d-none', type !== 'mobile_banking');
        });
    }

    $(document).on('change', '.method-type-select', toggleFields);
    toggleFields();
});
</script>
@endpush
@endsection
