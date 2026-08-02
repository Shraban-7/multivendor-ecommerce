@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('code_tone', 'error-code--danger')
@section('heading', __('Access denied'))
@section('message', __($exception->getMessage() ?: 'You do not have permission to view this page.'))
@section('badge')
    <span class="error-badge error-badge--danger">Forbidden</span>
@endsection
@section('actions')
    <a href="{{ url('/') }}" class="btn btn-primary">Go to homepage</a>
    <a href="javascript:history.back()" class="btn btn-secondary">Go back</a>
@endsection
