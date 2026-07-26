@extends('frontend.layouts.app')
@section('title', 'Product Review')

@section('content')
    <main class="review-page pb-5 sm:pb-10">
        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-[#F5F5F5] py-4 md:py-6">
            <nav class="container flex" aria-label="Breadcrumb">
                <ol class="rtl:gap-x-reverse inline-flex flex-wrap items-center gap-x-1 gap-y-2 md:gap-x-2">
                    <li class="inline-flex items-center">
                        <a href="/" class="text-davy-gray eq inline-flex items-center text-sm hover:text-primary">
                            <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="text-davy-gray eq inline-flex items-center text-sm hover:text-primary">
                            <svg class="text-davy-gray mx-1 h-3 w-3 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Product
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="text-davy-gray mx-1 h-3 w-3 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="text-butterfly-blue ms-1 text-sm md:ms-2">Review</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <section class="review-section section-padding container">
            <main class="max-w-3xl mx-auto w-full flex flex-col items-center">
                <!-- User Avatar -->
                <div class="w-24 h-24 rounded-full overflow-hidden mb-4 border border-gray-300 shadow-sm">
                    <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" class="w-full h-full object-cover" />
                </div>

                <!-- Heading -->
                <h1 class="text-2xl md:text-3xl font-semibold text-davy-gray mb-1 text-center">Share your experience</h1>
                <p class="text-base text-jet-gray mb-5 text-center max-w-md">
                    Your feedback helps us ensure top-quality service.
                </p>

                <form action="{{ route('orders.review', $product->id) }}" method="POST" enctype="multipart/form-data"
                    class="w-full space-y-6" id="review-form">
                    @csrf

                    <!-- Star Rating -->
                    <div id="stars-container" class="flex justify-center gap-2 mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                class="review-star text-3xl cursor-pointer {{ $i <= 3 ? 'text-yellow-400' : 'text-gray-300' }}"
                                data-rating="{{ $i }}">
                                <i class="fa-solid fa-star"></i>
                            </span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="star-rating" value="3">

                    <!-- Review Text -->
                    <textarea id="feedback-text" name="description" required
                        class="w-full p-4 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-primary text-base md:text-lg"
                        placeholder="Write your review here..." rows="2"></textarea>

                    <!-- Image Upload -->
                    <div>
                        <label for="image-input" class="block text-sm font-medium text-davy-gray mb-2">
                            Upload Images (Optional)
                        </label>

                        <div id="dropzone"
                            class="relative flex flex-col items-center justify-center w-full p-6 text-center border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 transition hover:border-primary">
                            <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5V19a2.5 2.5 0 002.5 2.5h13a2.5 2.5 0 002.5-2.5v-2.5M16 10l-4-4m0 0L8 10m4-4v12" />
                            </svg>
                            <p class="text-sm text-gray-500">Click or drag & drop to upload</p>
                            <input name="images[]" type="file" id="image-input" multiple
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                        </div>

                        <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col md:flex-row gap-3">
                        <button type="submit"
                            class="w-full bg-primary text-white py-3 rounded-lg hover:bg-theme-dark transition">
                            Submit Review
                        </button>
                        <a href="{{ route('orders.index') }}"
                            class="w-full text-center bg-gray-100 text-gray-800 py-3 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </main>
        </section>


        <!-- Review Section Ended -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let currentRating = 0;

                setStarState(currentRating);

                $('#stars-container').on('click', '.review-star', function() {
                    currentRating = $(this).data('rating');
                    setStarState(currentRating);
                    $('#star-rating').val(currentRating);
                });

                $('#stars-container').on('mouseover', '.review-star', function() {
                    const hoverRating = $(this).data('rating');
                    setStarState(hoverRating, true);
                });

                $('#stars-container').on('mouseout', function() {
                    setStarState(currentRating);
                });

                // $('#review-form').submit(function(e) {
                //     e.preventDefault();

                //     let formData = new FormData(this);

                //     $.ajax({
                //         url: '{{ route('orders.review', $product->id) }}',
                //         type: 'POST',
                //         headers: {
                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //         },
                //         data: formData,
                //         processData: false,
                //         contentType: false,
                //         success: function(response) {
                //             if (response.success) {
                //                 window.location.href = '{{ route('orders.index') }}';
                //             } else {
                //                 showSuccessToast(response.message);
                //             }
                //         },
                //         error: function(xhr) {
                //             console.error(xhr.responseText);
                //             toastr.danger('Submission flailed');
                //         }
                //     });
                // })

                function setStarState(rating, isHover = false) {
                    $('#stars-container .review-star').each(function() {
                        const starRating = $(this).data('rating');
                        if (isHover) {
                            $(this).toggleClass('active', starRating <= rating);
                        } else {
                            $(this).toggleClass('active', starRating <= rating);
                        }
                    });
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                const $dropzone = $('#dropzone');
                const $input = $('#image-input');
                const $previewContainer = $('#preview-container');

                let selectedFiles = [];

                $dropzone.on('dragover', function(e) {
                    e.preventDefault();
                    $dropzone.addClass('border-primary bg-gray-100');
                });

                $dropzone.on('dragleave', function() {
                    $dropzone.removeClass('border-primary bg-gray-100');
                });

                $dropzone.on('drop', function(e) {
                    e.preventDefault();
                    $dropzone.removeClass('border-primary bg-gray-100');
                    const files = Array.from(e.originalEvent.dataTransfer.files);
                    selectedFiles = selectedFiles.concat(files);
                    updateInputFiles();
                    showPreviews();
                });

                $input.on('change', function(e) {
                    const files = Array.from(e.target.files);
                    selectedFiles = selectedFiles.concat(files);
                    updateInputFiles();
                    showPreviews();
                });

                function showPreviews() {
                    $previewContainer.empty();
                    selectedFiles.forEach((file, index) => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const $wrapper = $('<div class="relative group">');

                            const $img = $('<img>', {
                                src: e.target.result,
                                class: 'w-full h-24 object-cover rounded-lg border border-gray-200'
                            });

                            const $removeBtn = $(`
                        <button type="button" data-index="${index}"
                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            &times;
                        </button>
                    `);

                            $removeBtn.on('click', function() {
                                const idx = $(this).data('index');
                                selectedFiles.splice(idx, 1);
                                updateInputFiles();
                                showPreviews();
                            });

                            $wrapper.append($img).append($removeBtn);
                            $previewContainer.append($wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function updateInputFiles() {
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    $input[0].files = dataTransfer.files;
                }
            });
        </script>
    @endpush
@endsection
