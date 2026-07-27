@extends('frontend.layouts.app')
@section('title', 'All Shops')

@section('content')
    <div class="max-w-[1400px] mx-auto px-2 sm:px-4">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#191919]">Our Shops</h1>
                <p class="text-xs text-[#767676] mt-0.5">{{ $sellers->total() }} sellers on {{ app_name() }}</p>
            </div>
            <form method="GET" action="{{ route('sellers.index') }}" class="relative w-full sm:w-72">
                <input type="text" name="name" placeholder="Search shop name..."
                    value="{{ request('name') }}"
                    class="w-full h-9 pl-4 pr-9 text-xs border border-[#E5E5E5] rounded-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] transition-colors duration-100 placeholder-[#767676]">
                <button type="submit"
                    class="absolute right-0 top-0 h-9 w-9 flex items-center justify-center text-[#767676] hover:text-[#F85606] transition-colors duration-100">
                    <i class="fas fa-search text-sm"></i>
                </button>
            </form>
        </div>

        <!-- Sellers Grid -->
        <div id="sellersContainer" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            @include('frontend.partials.seller-card')
        </div>

        <!-- Load More -->
        @if ($sellers->hasMorePages())
            <div class="mt-6 text-center pb-8">
                <button id="loadMoreSellers" data-page="{{ $sellers->currentPage() }}" data-url="{{ url()->current() }}"
                    class="inline-flex items-center gap-2 px-6 py-2 border border-[#F85606] text-[#F85606] text-xs font-semibold rounded-sm hover:bg-[#F85606] hover:text-white transition-colors duration-100"
                    type="button">
                    <span>Load More</span>
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#loadMoreSellers', function() {
            const button = $(this);
            let page = parseInt(button.data('page')) + 1;
            const url = button.data('url');

            $.ajax({
                url: url,
                method: 'GET',
                data: { page: page },
                beforeSend: function() {
                    button.prop('disabled', true).html(
                        '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                    );
                },
                success: function(response) {
                    if ($.trim(response) !== '') {
                        $('#sellersContainer').append(response);
                        button.data('page', page);
                        button.prop('disabled', false).html(
                            '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                        );
                    } else {
                        button.hide();
                    }
                },
                error: function() {
                    button.prop('disabled', false).html(
                        '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                    );
                }
            });
        });
    </script>
@endpush
