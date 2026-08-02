@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('code_tone', 'error-code--warning')
@section('heading', __('Page expired'))
@section('message', __('Your session has expired for security reasons. Please refresh and try again.'))
@section('badge')
    <span class="error-badge error-badge--warning">Expired</span>
@endsection
@section('actions')
    <a href="javascript:location.reload()" class="btn btn-primary">Refresh page</a>
    <a href="{{ url('/') }}" class="btn btn-secondary">Go to homepage</a>
@endsection
