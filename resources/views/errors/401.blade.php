@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('code_tone', '')
@section('heading', __('Sign in required'))
@section('message', __('You need to sign in to access this page. Please log in and try again.'))
@section('badge')
    <span class="error-badge error-badge--warning">Unauthorized</span>
@endsection
@section('actions')
    <a href="{{ url('/login') }}" class="btn btn-primary">Sign in</a>
    <a href="{{ url('/') }}" class="btn btn-secondary">Go to homepage</a>
@endsection
