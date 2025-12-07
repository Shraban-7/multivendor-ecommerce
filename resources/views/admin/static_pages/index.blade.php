@extends('admin.layouts.app')
@section('title', 'Static Pages')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Static Pages</h4>
    <a href="{{ route('admin.staticPages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Page
    </a>
</div>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Slug (URL Key)</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                <tr>
                                    <td>
                                        <h6 class="mb-0 text-sm ps-3">{{ $page->title }}</h6>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-muted">/pages/{{ $page->slug }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($page->is_active)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.staticPages.edit', $page->slug) }}" class="btn btn-sm btn-info text-white me-2" data-toggle="tooltip" title="Edit Page">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- Add Delete button here if needed --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection