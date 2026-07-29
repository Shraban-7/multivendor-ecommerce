@extends('admin.layouts.app')
@section('title','Banners')

@section('content')
<div class="container mt-4">

    <div class="flex justify-between items-center mb-3">
        <h3>Banners</h3>
        <!-- Add New Banner Button -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
            <i data-lucide="circle-plus"></i> Add Banner
        </button>
    </div>

    <table class="w-full text-left text-sm text-ink border-collapse">
        <thead class="bg-white">
            <tr>
                <th>#</th>
                <th>Preview</th>
                <!-- <th>Title</th> -->
                <th>Section</th>
                <th>Status</th>
                <th>Sort</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td>
                    @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" width="80" class="rounded">
                    @else
                    <span class="text-ink-tertiary">No Image</span>
                    @endif
                </td>
                <!-- <td>{{ $banner->title ?? '—' }}</td> -->
                <td><span class="badge bg-surface-muted">{{ $banner->section }}</span></td>
                <td>
                    @if($banner->is_active)
                    <span class="badge bg-feedback-success">Active</span>
                    @else
                    <span class="badge bg-feedback-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $banner->sort_order }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#bannerEditModal{{ $banner->id }}">
                        Edit
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"  class="inline"
                        onsubmit="return confirm('Delete this banner?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- ===================== Edit Modal for Each Banner ===================== -->
            <div class="modal fade" id="bannerEditModal{{ $banner->id }}" tabindex="-1"
                aria-labelledby="bannerEditModalLabel{{ $banner->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title" id="bannerEditModalLabel{{ $banner->id }}">Edit Banner #{{ $banner->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                @include('admin.banners.form-fields', ['banner' => $banner])
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center text-ink-tertiary">No banners found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $banners->links() }}
</div>

<!-- ===================== Add Banner Modal ===================== -->
<div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">Add New Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.banners.form-fields', ['banner' => null])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection