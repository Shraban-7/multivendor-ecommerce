@extends('seller.layouts.app')
@section('title', 'Payout Methods')

@section('content')
<div class="w-full px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('seller.payouts.index') }}" class="btn btn-light btn-sm">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
            </a>
            <h4 class="font-bold mb-0 text-ink">Payout Methods</h4>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Method
        </button>
    </div>

    @if ($methods->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($methods as $method)
                <div class="md:col-span-1 lg:col-span-1">
                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    @if ($method->method_type === 'bank')
                                        <div class="icon-bg-primary">
                                            <i data-lucide="building" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @elseif ($method->method_type === 'mobile_banking')
                                        <div class="icon-bg-success">
                                            <i data-lucide="smartphone" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @else
                                        <div class="icon-bg-warning">
                                            <i data-lucide="dollar-sign" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="font-semibold mb-0">{{ $method->methodLabel() }}</h6>
                                        @if ($method->is_default)
                                            <span class="badge-soft-success" style="font-size: 0.7rem;">Default</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                        <i data-lucide="more-vertical" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editMethodModal-{{ $method->id }}">
                                                <i data-lucide="edit" style="width: 14px; height: 14px;" class="me-2"></i> Edit
                                            </button>
                                        </li>
                                        @if (!$method->is_default)
                                            <li>
                                                <form method="POST" action="{{ route('seller.payouts.methods.default', $method) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">
                                                        <i data-lucide="star" style="width: 14px; height: 14px;" class="me-2"></i> Set as Default
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
                                                <button class="dropdown-item text-feedback-danger" type="submit">
                                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;" class="me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="text-sm">
                                <div class="flex justify-between mb-1">
                                    <span class="text-ink-tertiary">Account Name:</span>
                                    <span class="font-medium">{{ $method->account_name }}</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-ink-tertiary">Account Number:</span>
                                    <span class="font-medium">{{ $method->maskedAccountNumber() }}</span>
                                </div>
                                @if ($method->bank_name)
                                    <div class="flex justify-between mb-1">
                                        <span class="text-ink-tertiary">Bank:</span>
                                        <span class="font-medium">{{ $method->bank_name }}</span>
                                    </div>
                                @endif
                                @if ($method->mobile_provider)
                                    <div class="flex justify-between mb-1">
                                        <span class="text-ink-tertiary">Provider:</span>
                                        <span class="font-medium">{{ ucfirst($method->mobile_provider) }}</span>
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
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-5 text-center py-5">
                <i data-lucide="credit-card" style="width: 64px; height: 64px;" class="text-ink-tertiary mb-3"></i>
                <h5 class="font-semibold mb-2">No Payout Methods</h5>
                <p class="text-ink-tertiary mb-3">Add a payout method to start withdrawing your earnings.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                    <i data-lucide="plus" class="me-1" style="width: 16px; height: 16px;"></i> Add Payout Method
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
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