@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('code_tone', 'error-code--danger')
@section('heading', __('Something went wrong'))
@section('message', __('We hit an unexpected problem on our side. Please try again in a moment.'))
@section('badge')
    <span class="error-badge error-badge--danger">Server error</span>
@endsection
@section('actions')
    <a href="javascript:location.reload()" class="btn btn-primary">Try again</a>
    <a href="{{ url('/') }}" class="btn btn-secondary">Go to homepage</a>
@endsection
