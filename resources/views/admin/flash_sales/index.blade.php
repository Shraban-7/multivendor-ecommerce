@extends('admin.layouts.app')
@section('title', 'flash sales')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <h4>Flash Sales</h4>
        <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Flash Sale
        </a>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="p-5 p-0">
            <table class="w-full text-left text-sm text-ink border-collapse bg-white table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flashSales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->title }}</td>
                            <td>
                                @if ($sale->image)
                                    <img src="{{ storage_url($sale->image) }}" width="60">
                                @endif
                            </td>
                            <td>{{ $sale->start_time }}</td>
                            <td>{{ $sale->end_time }}</td>
                            <td>
                                @if ($sale->is_active)
                                    <span class="badge bg-feedback-success">Active</span>
                                @else
                                    <span class="badge bg-surface-muted">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.flash-sales.show', $sale->id) }}" class="btn btn-info btn-sm hover:bg-blue-700">
                                        View
                                    </a>
                                    <a href="{{ route('admin.flash-sales.edit', $sale->id) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ route('admin.flash-sales.delete', $sale->id) }}')">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
