@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Add Category</h4>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card card-body">
                <form id="form" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @CSRF
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Cover Title</label>
                            <input name="cover_title" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Cover Description</label>
                            <x-textarea-input name="cover_description" value="" />
                        </div>
                         <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value="" class="form-control form-control-color" required>
                        </div>
                         <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value="" class="form-control form-control-color" required>
                        </div>
                         <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value="" class="form-control form-control-color" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="gap-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_nav" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Show Top Navbar</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_special" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Special</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_slider" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Slider Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Image</label>
                            <x-image-input name="image" />
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Cover Photo</label>
                            <x-image-input name="cover_image" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-theme">Save</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

        </script>
    @endpush
@endsection
