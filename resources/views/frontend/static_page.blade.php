@extends('frontend.layouts.app')
@section('title', $page->title)
@section('content')
<div class="container">
    <div class="mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 relative">
                {{ $page->title }}
                <span class="block h-1 w-20 bg-primary-500 mt-2 rounded-full"></span>
            </h1>
            <div class="space-y-6 text-gray-600 leading-relaxed text-sm md:text-base">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection