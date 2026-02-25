@extends('errors.error-layout')

@section('code', '401')
@section('title', __('Unauthorized'))
@section('description', __('You need to log in to access this page. Please sign in with your credentials and try again.'))

@section('icon')
{{-- Lock icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    <circle cx="12" cy="16" r="1"/>
</svg>
@endsection
