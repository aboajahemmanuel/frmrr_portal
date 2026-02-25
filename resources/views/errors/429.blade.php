@extends('errors.error-layout')

@section('code', '429')
@section('title', __('Too Many Requests'))
@section('description', __('You\'ve sent too many requests in a short period. Please wait a moment and try again.'))

@section('icon')
{{-- Zap / lightning icon --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
</svg>
@endsection
