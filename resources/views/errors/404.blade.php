@extends('errors::minimal')

@section('title', __('Page Not Found'))
@section('code', '404')
@section('heading', __('Page not found'))
@section('message', __('The page you are looking for may have been moved, renamed, or is no longer available.'))
@section('badge')
    <span class="error-badge">Not found</span>
@endsection
@section('actions')
    <a href="{{ url('/') }}" class="btn btn-primary">Go to homepage</a>
    <a href="javascript:history.back()" class="btn btn-secondary">Go back</a>
@endsection
