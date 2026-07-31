@php
    $counts = $counts ?? ['total' => 0, 'this_month' => 0, 'this_year' => 0];
@endphp
@extends('seller.layouts.app')
@section('title', 'Expenses')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #ef4444, #f87171, #fca5a5);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="receipt" class="text-feedback-danger" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Expenses</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Expenses</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-danger/15 text-feedback-danger">
                        <i data-lucide="trending-down" style="width:11px;height:11px;" class="me-1"></i> Outflows
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Track shop expenses outside of payouts — packaging, marketing, returns handling and more.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
                <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Expense
            </button>
        </div>
    </div>
</section>

@php
    $tiles = [
        ['key' => 'total',      'label' => 'Total Expenses',  'top' => '#ef4444', 'text' => 'text-feedback-danger',  'icon' => 'receipt'],
        ['key' => 'this_month', 'label' => 'This Month',        'top' => '#b7791a', 'text' => 'text-feedback-warning',  'icon' => 'calendar'],
        ['key' => 'this_year',  'label' => 'This Year',         'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'bar-chart-2'],
    ];
@endphp
<section class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ money($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Expenses</h3>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $expenses->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $expenses->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $expenses->total() }}</span> expenses
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Category</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Amount</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Description</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-muted text-ink-emphasis">
                                {{ $expense->category->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-feedback-danger">{{ money($expense->amount) }}</td>
                        <td class="px-4 py-3 text-sm text-ink-secondary" style="max-width: 280px;">
                            <div class="truncate">{{ $expense->description ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary whitespace-nowrap">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $expense->expense_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <button class="btn btn-light btn-sm edit-expense-btn"
                                        data-id="{{ $expense->id }}"
                                        data-category="{{ $expense->seller_expense_category_id }}"
                                        data-category-name="{{ $expense->category->name ?? '' }}"
                                        data-amount="{{ $expense->amount }}"
                                        data-description="{{ $expense->description }}"
                                        data-date="{{ date('Y-m-d') }}"
                                        data-bs-toggle="modal" data-bs-target="#editExpenseModal">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm text-feedback-danger delete-expense-btn"
                                        data-id="{{ $expense->id }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteExpenseModal">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="py-10 text-center">
                                <i data-lucide="receipt" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No expenses yet</p>
                                <p class="text-ink-tertiary text-xs">Track your first outflow to keep books clean.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $expenses->links() }}
    </div>
</section>

{{-- Create Expense Modal --}}
<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('seller.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-bold">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Category</label>
                        <select name="seller_expense_category_id" id="create-category"
                                class="brand-select w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Amount</label>
                        <input type="number" step="0.01" name="amount"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Description</label>
                        <select name="description" id="create-description"
                                class="description-select w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($descriptions as $desc)
                                <option value="{{ $desc }}">{{ $desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Date</label>
                        <input type="date" name="expense_date"
                               value="{{ old('expense_date', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Expense Modal --}}
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editExpenseForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-bold">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Category</label>
                        <select name="seller_expense_category_id" id="edit-category"
                                class="select2-category w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="" selected disabled>Select or Create Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Amount</label>
                        <input type="number" step="0.01" name="amount" id="edit-amount"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Description</label>
                        <select name="description" id="edit-description"
                                class="description-select w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="" selected disabled>Select or Create Description</option>
                            @foreach ($descriptions as $desc)
                                <option value="{{ $desc }}">{{ $desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Date</label>
                        <input type="date" name="expense_date" id="edit-date"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteExpenseForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-bold text-feedback-danger">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-feedback-danger/10 p-4 rounded-xs flex items-start gap-3">
                        <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                        <div class="text-sm text-ink-soft">Are you sure you want to delete this expense? This action cannot be undone.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                            $editDescription.find("option[value='" + desc + "']").prop("selected", true);
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

@endsection
