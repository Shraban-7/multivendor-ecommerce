@extends('frontend.layouts.app')
@section('title', 'Product Review')

@section('content')
    <main class="review-page pb-5 sm:pb-10">
        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
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

        <!-- Review Section   Starts -->
        <section class="review-section section-padding container">
            <main class="max-w-3xl mx-auto w-full flex flex-col items-center">
                <!-- Profile Image -->
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-3 md:mb-4">
                    <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" />
                </div>

                <!-- Heading -->
                <h1 class="text-xl sm:text-3xl font-semibold text-davy-gray mb-1">
                    Share your experience
                </h1>
                <p class="text-jet-gray mb-3 md:mb-5 text-center">
                    Your feedback help us to ensure best quality service
                </p>

                <!-- Stars Rating -->
                <div class="flex flex-nowrap gap-x-1 md:gap-x-2 mb-3 md:mb-5" id="stars-container">
                    <!-- by default 3 star is active -->
                    <span class="review-star cursor-pointer text-3xl md:text-4xl active" data-rating="1">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="review-star cursor-pointer text-3xl md:text-4xl active" data-rating="2">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="review-star cursor-pointer text-3xl md:text-4xl active" data-rating="3">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="review-star cursor-pointer text-3xl md:text-4xl" data-rating="4">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="review-star cursor-pointer text-3xl md:text-4xl" data-rating="5">
                        <i class="fa-solid fa-star"></i>
                    </span>
                </div>
                <input type="hidden" name="rating" id="star-rating">

                <!-- Feedback Text Area -->
                <textarea id="feedback-text" name="review_text"
                    class="w-full p-5 md:p-6 bg-jet-gray/10 rounded-xl md:rounded-2xl mb-4 md:mb-5 h-32 md:h-36 focus:outline-none focus:ring[2px] md:focus:ring-2 focus:ring-light-yellow border-0 text-lg md:text-xl eq"
                    placeholder="Provide your complement"></textarea>

                <!-- Buttons -->
                <button id="submit-btn"
                    class="w-full bg-primary text-theme-light py-2 md:py-3 rounded-lg md:rounded-xl mb-3 hover:bg-theme-dark eq">
                    Submit
                </button>
                <a href="{{ route('orders.details', $order->id) }}"
                    class="block text-center w-full bg-jet-gray/10 text-theme-dark py-2 md:py-3 rounded-lg md:rounded-xl hover:bg-theme-dark hover:text-theme-light eq">
                    Cancel
                </a>

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

                $('#submit-btn').click(function(e) {
                    e.preventDefault();

                    const rating = currentRating;
                    const reviewText = $('#feedback-text').val().trim();

                    const data = {
                        rating: rating,
                        review_text: reviewText
                    };

                    $.ajax({
                        url: '{{ route('orders.review', $order->id) }}',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        success: function(result) {
                            if (result.success) {
                                window.location.href = '{{ route('orders.details', $order->id) }}';
                            } else {
                                alert(result.message || 'Something went wrong!');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                });

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
    @endpush
@endsection
