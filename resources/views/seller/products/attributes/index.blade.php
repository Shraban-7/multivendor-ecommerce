@extends('seller.layouts.app')
@section('title', 'Product Attributes')

@section('content')
    <div class="flex justify-between items-end mb-3">
        <div>
            <h4 class="font-bold mb-0">Product Attributes</h4>
            <small class="text-ink-tertiary">Manage custom product attributes and their options</small>
        </div>
    </div>

    @php
        $allOptions = collect();
    @endphp

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-2.5">Name</th>
                        <th class="px-4 py-2.5">Options</th>
                        <th class="px-4 py-2.5">Date</th>
                        <th class="px-4 py-2.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($productAttributes as $productAttribute)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3 font-semibold align-top">{{ $productAttribute->name }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-col gap-2">
                                    @foreach ($productAttribute->options as $option)
                                        <div class="border border-border bg-surface-muted rounded-xs p-2 flex items-center justify-between">
                                            <span class="text-sm font-semibold">{{ $option->value }}</span>
                                            <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteOptionModal-{{ $option->id }}">
                                                <i data-lucide="trash" class="icon-xs"></i>
                                            </button>
                                        </div>
                                        @php $allOptions->push($option); @endphp
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top">{{ $productAttribute->created_at->format('d-m-y h:i A') }}</td>
                            <td class="px-4 py-3 align-top text-right">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productAttribute->id }}">
                                    <i data-lucide="trash" class="icon-xs"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($allOptions as $option)
        <div class="modal fade" id="deleteOptionModal-{{ $option->id }}" tabindex="-1"
             aria-labelledby="deleteOptionModalLabel-{{ $option->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteOptionModalLabel-{{ $option->id }}">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-center gap-3" role="alert">
                            <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                            <span>Are you sure you want to delete this option?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('seller.productAttributes.option_delete', $option->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($productAttributes as $productAttribute)
        <div class="modal fade" id="deleteModal-{{ $productAttribute->id }}" tabindex="-1"
            aria-labelledby="deleteModalLabel-{{ $productAttribute->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $productAttribute->id }}">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="text-center modal-body">
                        <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3" role="alert">
                            <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                            <p class="mt-1 text-ink-secondary">Are you sure you want to delete this Product Attribute?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('seller.productAttributes.delete', $productAttribute->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.remove-option-btn').on('click', function() {
                    const row = $(this).closest('.option-row');
                    const optionId = row.data('option-id');
                    const deletedContainer = row.closest('.modal-body').find(
                        '[id^="deleted-options-container"]');

                    if (optionId) {
                        deletedContainer.append(
                            `<input type="hidden" name="deleted_option_ids[]" value="${optionId}">`
                        );
                    }

                    row.remove();
                });
            });
        </script>
    @endpush


@endsection