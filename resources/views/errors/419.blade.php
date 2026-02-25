@extends('errors.error-layout')

@section('code', '419')
@section('title', __('Page Expired'))
@section('description', __('Your session has expired. Please refresh the page or log in again to continue.'))

@section('icon')
{{-- Clock icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"/>
    <polyline points="12 6 12 12 16 14"/>
</svg>
@endsection
