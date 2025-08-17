@extends('adminlte::page')

@section('title', 'Nova Empresa')

@section('content_header')
    <h1>Nova Empresa</h1>
@endsection

@section('content')
    @livewire('admin.companies.company-form')
@endsection
