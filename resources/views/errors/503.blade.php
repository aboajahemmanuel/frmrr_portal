@extends('errors.error-layout')

@section('code', '503')
@section('title', __('Service Unavailable'))
@section('description', __('We\'re currently performing scheduled maintenance. We\'ll be back online shortly. Thank you for your patience.'))

@section('icon')
{{-- Wrench icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
</svg>
@endsection
