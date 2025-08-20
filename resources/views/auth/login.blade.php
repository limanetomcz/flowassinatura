@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
$loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
$passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

if (config('adminlte.use_route_url', false)) {
$loginUrl = $loginUrl ? route($loginUrl) : '';
$passResetUrl = $passResetUrl ? route($passResetUrl) : '';
} else {
$loginUrl = $loginUrl ? url($loginUrl) : '';
$passResetUrl = $passResetUrl ? url($passResetUrl) : '';
}
@endphp

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('auth_body')
@livewire('login-form')
@stop

@section('auth_footer')
{{-- Password reset link --}}
@if($passResetUrl)
<p class="my-0">
    <a href="{{ $passResetUrl }}">
        {{ __('adminlte::adminlte.i_forgot_my_password') }}
    </a>
</p>
@endif


@stop