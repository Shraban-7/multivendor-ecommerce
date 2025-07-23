<div id="quick-view-modal-{{ $product['id'] }}" tabindex="-1"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
    aria-hidden="true" inert>
    <div class="relative container max-h-full">
        <!-- Modal content -->
        <div class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl">
            <!-- Modal Close Triger -->
            <button type="button"
                class="close-modal-btn text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                data-modal-hide="quick-view-modal-{{ $product['id'] }}">
                <i class="fas fa-x"></i>
                <!-- <i class="fa-solid fa-xmark"></i> Font Awesome fontawesome.com -->
                <span class="sr-only">Close modal</span>
            </button>
            <!-- Modal body -->
            <div class="p-4 md:p-10">
                <x-frontend.product-contents :product="$product" />
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function openModal(modal) {
            modal.removeAttribute('inert');
            modal.setAttribute('aria-hidden', 'false');
            modal.classList.remove('hidden');
            const closeBtn = modal.querySelector('[data-modal-hide]');
            closeBtn?.focus();
        }

        function closeModal(modal) {
            modal.setAttribute('inert', '');
            modal.setAttribute('aria-hidden', 'true');
            modal.classList.add('hidden');
        }

        $(document).on('click', '[data-modal-toggle]', function() {
            const id = $(this).data('modal-target') || $(this).data('modal-toggle');
            const modal = document.getElementById(id);
            if (modal) openModal(modal);
        });

        $(document).on('click', '[data-modal-hide]', function() {
            const id = $(this).data('modal-hide');
            const modal = document.getElementById(id);
            if (modal) closeModal(modal);
        });
    </script>
@endpush
