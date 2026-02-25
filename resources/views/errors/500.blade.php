@extends('errors.error-layout')

@section('code', '500')
@section('title', __('Server Error'))
@section('description', __('Something went wrong on our end. We\'re aware of the issue and working to fix it. Please try again later.'))

@section('icon')
{{-- Alert-triangle icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
</svg>
@endsection
