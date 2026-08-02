@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('code_tone', 'error-code--info')
@section('heading', __('We will be right back'))
@section('message', __('The shop is temporarily unavailable for maintenance. Please check back shortly.'))
@section('badge')
    <span class="error-badge error-badge--info">Unavailable</span>
@endsection
@section('actions')
    <a href="javascript:location.reload()" class="btn btn-primary">Try again</a>
    <a href="{{ url('/') }}" class="btn btn-secondary">Go to homepage</a>
@endsection
