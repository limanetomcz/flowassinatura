@extends('adminlte::auth.login')

@section('auth_footer')
    @if (session('status'))
        <div class="alert alert-danger text-center" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Esqueceu sua senha?') }}
        </a>
    @endif

@endsection