@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    @if (session('status'))
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('status') }}
        </x-adminlte-alert>
    @endif

    <p>Você está logado com sucesso.</p>
@endsection
