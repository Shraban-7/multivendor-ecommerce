@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('code_tone', 'error-code--warning')
@section('heading', __('Too many requests'))
@section('message', __('You have sent too many requests in a short time. Please wait a moment and try again.'))
@section('badge')
    <span class="error-badge error-badge--warning">Rate limited</span>
@endsection
@section('actions')
    <a href="javascript:location.reload()" class="btn btn-primary">Try again</a>
    <a href="{{ url('/') }}" class="btn btn-secondary">Go to homepage</a>
@endsection
