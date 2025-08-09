@extends('adminlte::auth.login')

@section('auth_footer')

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Esqueceu sua senha?') }}
        </a>
    @endif

@endsection