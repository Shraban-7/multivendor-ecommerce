@extends('admin.layouts.app')
@section('title', 'Promo Posters')
@section('content')
    <div class="mb-3 flex justify-between items-end">
        <h4 class="mb-0">Home Promo Poster</h4>
        @if (hasPermission('admin.settings.posters.store'))
            @if (count($posters) < 4)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i data-feather="plus" class="icon-xs"></i> Add Promo Poster
                </button>
            @endif
        @endif
    </div>
    <div class="grid grid-cols-1">
        @foreach ($posters as $poster)
            <div class="md:col-span-1 lg:col-span-1 mb-3">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow-sm h-full">
                    <img src="{{ storage_url($poster->image) }}" class="card-img-top border-b" alt="Banner Image">
                    <div class="p-5">
                        <h3 class="text-lg font-semibold mb-2">{{ $poster->title }}</h3>
                        <p class="card-text text-ink-tertiary mb-1">{{ $poster->subtitle }}</p>
                        <p class="mb-1"><strong>Button Text:</strong> {{ $poster->button_text }}</p>
                        <p class="mb-1"><strong>Link:</strong> <a href="{{ $poster->button_link }}"
                                target="_blank">{{ $poster->button_link }}</a></p>
                        <p class="mb-2"><strong>Position:</strong> {{ $poster->position }}</p>

                        @if (hasPermission('admin.settings.posters.update'))
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $poster->id }}">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal-{{ $poster->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title text-base">Edit Promo Poster</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.settings.posters.update', $poster->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="grid grid-cols-1">
                                    <div class="mb-3 md:col-span-full">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Position</label>
                                        <select name="position" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                            <option value="" disabled>--Choose--</option>
                                            @for ($i = 1; $i <= 2; $i++)
                                                <option value="{{ $i }}"
                                                    @if ($poster->position == $i) selected
                                                @elseif (in_array($i, $usedPositions) && $poster->position != $i) disabled @endif>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3 col-span-full">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Title</label>
                                        <input name="title" type="text" value="{{ $poster->title }}"
                                            class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                    </div>
                                    <div class="mb-3 col-span-full">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Link</label>
                                        <input name="link" type="text" value="{{ $poster->link }}"
                                            class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                    </div>
                                    <div class="mb-3 md:col-span-full">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Upload New Image (optional)</label>
                                        <input name="image" type="file" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                        @if ($poster->image)
                                            <div class="mt-2">
                                                <label class="block text-xs font-medium text-ink-secondary mb-1 block">Current Image:</label>
                                                <img src="{{ storage_url($poster->image) }}" alt="Current Banner Image"
                                                    class="img-fluid rounded shadow" style="max-height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-base">Add Promo Poster</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.settings.posters.store') }}" method="post" enctype="multipart/form-data">
                    @CSRF
                    <div class="modal-body">
                        <div class="grid grid-cols-1">
                            <div class="mb-3 md:col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Position</label>
                                <select name="position" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="positionSelect" required>
                                    <option value="" selected disabled>--Choose--</option>
                                    @foreach ($availablePositions as $position)
                                        <option value="{{ $position }}">{{ $position }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Title</label>
                                <input name="title" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="mb-3 col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Link</label>
                                <input name="link" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="mb-3 md:col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Upload</label>
                                <input name="image" type="file" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush

@endsection
