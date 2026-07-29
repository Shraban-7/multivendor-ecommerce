@extends('admin.layouts.app')
@section('title', 'Flash Sales')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Flash Sales</h1>
            <p class="text-sm text-ink-secondary mt-1">Manage time-limited promotional sales</p>
        </div>
        <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="icon-xs"></i> Add Flash Sale
        </a>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($flashSales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>
                                @if ($sale->image)
                                    <img src="{{ storage_url($sale->image) }}" width="50" class="border rounded-xs">
                                @else
                                    <span class="text-ink-tertiary text-xs">—</span>
                                @endif
                            </td>
                            <td class="font-medium text-ink">{{ $sale->title }}</td>
                            <td class="text-ink-secondary text-xs">{{ $sale->start_time }}</td>
                            <td class="text-ink-secondary text-xs">{{ $sale->end_time }}</td>
                            <td>
                                @if ($sale->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.flash-sales.show', $sale->id) }}" class="btn btn-sm btn-light">
                                        <i data-lucide="eye" class="icon-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.flash-sales.edit', $sale->id) }}" class="btn btn-sm btn-light">
                                        <i data-lucide="edit" class="icon-xs"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('admin.flash-sales.delete', $sale->id) }}')">
                                        <i data-lucide="trash" class="icon-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-ink-tertiary">No flash sales found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection