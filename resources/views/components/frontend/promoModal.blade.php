<div id="promoPopup" class="hidden opacity-0 fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative bg-white rounded-3xl overflow-hidden shadow-2xl max-w-4xl w-[92%] md:w-[85%] lg:w-[70%] animate-[fadeIn_0.4s_ease-out] md:flex">
        <button id="closePromoBtn"
            class="absolute top-4 right-4 z-20 w-9 h-9 flex items-center justify-center 
                   bg-white/90 border border-gray-200 rounded-full shadow-sm
                   hover:bg-primary-50 hover:text-primary-600 transition">
            <i class="fa-solid fa-times text-lg"></i>
        </button>

        <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center" style="background-image: url('{{ storage_url($banner->image) }}');"></div>
        <div class="w-full md:w-1/2 p-10 md:p-12 text-center flex flex-col justify-center bg-gradient-to-br from-white to-orange-50">
            <span class="text-primary-600 font-semibold tracking-widest text-xs md:text-sm mb-3">{{ $banner->title }}</span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $banner->subtitle }}</h2>
            <p class="text-gray-600 mb-8 text-sm md:text-base leading-relaxed">{{ $banner->description }}</p>

            <div class="space-y-3">
                <button class="close-promo-trigger w-full bg-primary-600 text-white font-semibold py-3.5 rounded-lg 
                           hover:bg-primary-700 transition shadow-lg shadow-primary-500/30 text-base">
                    {{ $banner->button_text }}
                </button>
                <button class="close-promo-trigger text-gray-400 text-xs underline hover:text-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>