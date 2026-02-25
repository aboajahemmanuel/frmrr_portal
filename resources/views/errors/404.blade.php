@extends('errors.error-layout')

@section('code', '404')
@section('title', __('Page Not Found'))
@section('description', __('The page you\'re looking for doesn\'t exist or has been moved. Check the URL or head back to the homepage.'))

@section('icon')
{{-- Search / magnifying-glass icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="11" cy="11" r="8"/>
    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
    <line x1="8" y1="11" x2="14" y2="11"/>
</svg>
@endsection
