@extends('frontend.layouts.app')
@section('title', "Shops")

@section('content')

<div class="min-h-100">
    <h2 class="font-semibold uppercase sm:text-xl md:text-2xl text-theme-dark mb-5">
        Shops
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6">
        @foreach ($sellers as $seller)
        <div class="bg-white rounded-md shadow-sm hover:shadow-md transition duration-200 text-center px-4 py-6">
            <img src="{{ storage_url($seller->image) }}"
                alt="{{ $seller->name }}"
                class="w-20 h-20 mx-auto object-cover mb-4">

            <h3 class="text-base font-medium text-gray-800 truncate">{{ $seller->name }}</h3>

            <a href="{{ route('sellers.shop', $seller->username) }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block transition">
                Visit Store →
            </a>
        </div>
        @endforeach
    </div>
</div>

@endsection