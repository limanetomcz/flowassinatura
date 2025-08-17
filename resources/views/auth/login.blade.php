@extends('adminlte::auth.auth-page', ['authType' => 'login'])

{{-- Section to add CSS before the main AdminLTE CSS --}}
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
    // Get URLs for login, register, and password reset pages
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    // If routes should be generated using named routes
    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        // Otherwise, use plain URLs
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

{{-- Header of the authentication card --}}
@section('auth_header', __('adminlte::adminlte.login_message'))

{{-- Body of the authentication card --}}
@section('auth_body')
    {{-- Include the Livewire login form component --}}
    @livewire('login-form')
@stop

{{-- Footer of the authentication card --}}
@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Registration link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
