@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('heading', __('Payment required'))
@section('message', __('This content requires a payment or active subscription before you can continue.'))
@section('badge')
    <span class="error-badge">Payment</span>
@endsection
@section('actions')
    <a href="{{ url('/') }}" class="btn btn-primary">Go to homepage</a>
    <a href="javascript:history.back()" class="btn btn-secondary">Go back</a>
@endsection
