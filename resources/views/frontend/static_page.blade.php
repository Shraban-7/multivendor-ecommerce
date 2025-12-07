@extends('frontend.layouts.app')
@section('title', $page->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 mb-4 text-center">{{ $page->title }}</h1>
            <hr class="mb-5">
            <div class="card shadow-sm p-4 p-md-5">
                <div class="card-body">
                    <div class="static-page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            <!-- <p class="text-muted text-end mt-3">Last updated: {{ $page->updated_at->format('M d, Y') }}</p> -->
        </div>
    </div>
</div>
@endsection