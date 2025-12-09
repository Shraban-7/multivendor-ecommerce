@extends('admin.layouts.app')
@section('title','flash sales')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Flash Sales</h4>
        <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Flash Sale
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
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
                    @foreach($flashSales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->title }}</td>
                        <td>
                            @if($sale->image)
                            <img src="{{ asset('uploads/flash_sale/'.$sale->image) }}" width="60">
                            @endif
                        </td>
                        <td>{{ $sale->start_time }}</td>
                        <td>{{ $sale->end_time }}</td>
                        <td>
                            @if($sale->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.flash-sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                View
                            </a>
                            <a href="{{ route('admin.flash-sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection