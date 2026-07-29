@extends('admin.layouts.app')
@section('title', 'Static Pages')

@section('content')

<div class="flex justify-between items-end mb-3">
    <h4 class="mb-0">Static Pages</h4>
    <a href="{{ route('admin.staticPages.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="me-1"></i> Add New Page
    </a>
</div>

<div class="overflow-x-auto p-0">
    <table class="w-full text-left text-sm text-ink border-collapse">
        <thead>
            <tr>
                <th class="uppercase text-ink-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                <th class="uppercase text-ink-secondary text-xxs font-weight-bolder opacity-7 ps-2">Slug (URL Key)</th>
                <th class="text-center uppercase text-ink-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-ink-secondary opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
            <tr>
                <td>
                    <h5 class="mb-0">{{ $page->title }}</h5>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0 text-ink-tertiary">/pages/{{ $page->slug }}</p>
                </td>
                <td class="align-middle text-center text-sm">
                    @if($page->is_active)
                    <span class="badge bg-feedback-success">Active</span>
                    @else
                    <span class="badge bg-feedback-danger">Inactive</span>
                    @endif
                </td>
                <td class="align-middle">
                    <a href="{{ route('admin.staticPages.edit', $page->slug) }}" class="btn btn-info btn-sm hover:bg-blue-700 me-2" data-toggle="tooltip" title="Edit Page">
                        <i data-lucide="pencil"></i> Edit
                    </a>
                    {{-- Add Delete button here if needed --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection