@extends('seller.layouts.app')
@section('title', 'Product Attributes')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h4 class="font-bold mb-0 text-ink">Product Attributes</h4>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse mb-3 bg-white table-bordered table-hover">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Name</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Options</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productAttributes as $productAttribute)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $productAttribute->name }}</div>
                        </td>
                        <td>
                            @foreach ($productAttribute->options as $option)
                                <div class="flex items-center justify-between mb-2 border rounded-xs p-2">
                                    <div>
                                        <small class="font-semibold">{{ $option->value }}</small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteOptionModal-{{ $option->id }}">
                                            <i data-feather="trash" class="icon-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteOptionModal-{{ $option->id }}" tabindex="-1"
                                     aria-labelledby="deleteOptionModalLabel-{{ $option->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteOptionModalLabel-{{ $option->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-center gap-3" role="alert">
                                                    <i class="bi bi-exclamation-circle-fill me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                                                    <span>Are you sure you want to delete this option?</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('seller.productAttributes.option_delete', $option->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <td>{{ $productAttribute->created_at->format('d-m-y h:i A') }}</td>
                        <td class="flex">


                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productAttribute->id }}">
                                <i data-feather="trash" class="icon-xs"></i> Delete
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="deleteModal-{{ $productAttribute->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $productAttribute->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel-{{ $productAttribute->id }}">Confirm
                                        Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body">
                                    <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3" role="alert">
                                        <i class="bi bi-exclamation-circle-fill me-2 text-feedback-danger"
                                            style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-ink-secondary">
                                            Are you sure you want to delete this Product Attribute?
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('seller.productAttributes.delete', $productAttribute->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

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