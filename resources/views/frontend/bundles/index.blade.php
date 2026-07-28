@extends('frontend.layouts.app')
@section('title', 'Product Bundles')

@section('content')
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-8">
        <nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="font-medium text-gray-900" aria-current="page">Bundles</li>
            </ol>
        </nav>
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Product <span class="text-primary-600">Bundles</span></h1>
            <p class="text-gray-500 mt-2">Save more with curated product bundles</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    @if($bundles->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            @foreach($bundles as $bundle)
                <x-frontend.bundle-card :bundle="$bundle" />
            @endforeach
        </div>
        <div class="mt-8">
            {{ $bundles->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
            <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-gift text-4xl text-primary-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Bundles Available</h3>
            <p class="text-gray-500 mb-6">There are no product bundles at the moment. Check back later!</p>
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition shadow-lg">Back to Home</a>
        </div>
    @endif
</div>
@endsection
