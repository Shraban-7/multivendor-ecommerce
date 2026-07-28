@extends('seller.layouts.app')
@section('title', 'Product Attributes')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0 text-dark">Product Attributes</h4>
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="small fw-semibold text-muted">Name</th>
                    <th scope="col" class="small fw-semibold text-muted">Options</th>
                    <th scope="col" class="small fw-semibold text-muted">Date</th>
                    <th scope="col" class="small fw-semibold text-muted">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productAttributes as $productAttribute)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $productAttribute->name }}</div>
                        </td>
                        <td>
                            @foreach ($productAttribute->options as $option)
                                <div class="d-flex align-items-center justify-content-between mb-2 border rounded p-2">
                                    <div>
                                        <small class="fw-semibold">{{ $option->value }}</small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1" title="Delete"
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
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                                                    <span>Are you sure you want to delete this option?</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('seller.productAttributes.option_delete', $option->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <td>{{ $productAttribute->created_at->format('d-m-y h:i A') }}</td>
                        <td class="d-flex">


                            <button type="submit" class="border btn btn-danger btn-sm d-inline-flex align-items-center gap-1" title="Delete"
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
                                    <div class="alert alert-warning d-flex" role="alert">
                                        <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                            style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-secondary">
                                            Are you sure you want to delete this Product Attribute?
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('seller.productAttributes.delete', $productAttribute->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">Delete</button>
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
