@extends('seller.layouts.app')
@section('title', 'Expenses')
@section('content')

    <div class="mb-2 rounded d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Expenses</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
            <i data-feather="plus" class="icon-xs"></i> Add Expense
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->category->name ?? '' }}</td>
                        <td><span class="text-dark">{{ money($expense->amount) }}</span></td>
                        <td>{{ $expense->description ?? '' }}</td>
                        <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-light border btn-sm me-1 edit-expense-btn" data-id="{{ $expense->id }}"
                                data-category="{{ $expense->seller_expense_category_id }}"
                                data-category-name="{{ $expense->category->name ?? '' }}"
                                data-amount="{{ $expense->amount }}" data-description="{{ $expense->description }}"
                                data-date="{{ date('Y-m-d') }}" data-bs-toggle="modal"
                                data-bs-target="#editExpenseModal">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                            
                            <button type="button" class="btn btn-danger border btn-sm delete-expense-btn"
                                data-id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#deleteExpenseModal">
                                <i data-feather="trash" class="icon-xs"></i> Delete
                            </button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Create Expense Modal -->
    <div class="modal fade" id="createExpenseModal" tabindex="-1" aria-labelledby="createExpenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('seller.expenses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createExpenseModalLabel">Add Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="seller_expense_category_id" id="create-category"
                                class="form-select w-100 brand-select" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <select name="description" id="create-description" class="form-select w-100 description-select">
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($descriptions as $desc)
                                    <option value="{{ $desc }}">{{ $desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editExpenseForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="seller_expense_category_id" id="edit-category"
                                class="form-control select2-category" required>
                                <option value="" selected disabled>Select or Create Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" id="edit-amount" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <option value="" selected disabled>Select or Create Description</option>
                            <select name="description" id="edit-description"
                                class="form-select w-100 description-select">
                                @foreach ($descriptions as $desc)
                                    <option value="{{ $desc }}">{{ $desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="expense_date" id="edit-date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteExpenseForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteExpenseModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this expense?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#create-category").select2({
                tags: true,
                theme: "bootstrap-5",
                dropdownParent: $("#createExpenseModal")
            });

            $("#edit-category").select2({
                tags: true,
                theme: "bootstrap-5",
                dropdownParent: $("#editExpenseModal")
            });

            $("#create-description").select2({
                tags: true,
                theme: "bootstrap-5",
                dropdownParent: $("#createExpenseModal")
            });

            $("#edit-description").select2({
                tags: true,
                theme: "bootstrap-5",
                dropdownParent: $("#editExpenseModal")
            });

            $(document).on("click", ".edit-expense-btn", function() {
                let id = $(this).data("id");
                let category = $(this).data("category");
                let categoryName = $(this).data("category-name");
                let amount = $(this).data("amount");
                let description = $(this).data("description");
                let date = $(this).data("date");

                let action = "{{ route('seller.expenses.update', ':id') }}".replace(':id', id);
                $("#editExpenseForm").attr("action", action);

                let $editCategory = $("#edit-category");
                if (category && $editCategory.find("option[value='" + category + "']").length === 0) {
                    $editCategory.append(new Option(categoryName, category, true, true)).trigger('change');
                } else {
                    $editCategory.val(category).trigger('change');
                }

                $("#edit-amount").val(amount);
                $("#edit-date").val(date);

                let $editDescription = $("#edit-description");
                $editDescription.val(null).trigger('change');

                if (description) {
                    let descriptions = description.split(',').map(d => d.trim());
                    descriptions.forEach(function(desc) {
                        if ($editDescription.find("option[value='" + desc + "']").length === 0) {
                            $editDescription.append(new Option(desc, desc, true, true));
                        } else {
                            $editDescription.find("option[value='" + desc + "']").prop("selected",
                                true);
                        }
                    });
                    $editDescription.trigger('change');
                }
            });

            $(document).on("click", ".delete-expense-btn", function() {
                let id = $(this).data("id");

                let action = "{{ route('seller.expenses.destroy', ':id') }}";
                action = action.replace(":id", id);

                $("#deleteExpenseForm").attr("action", action);
            });
        });
    </script>
@endpush
