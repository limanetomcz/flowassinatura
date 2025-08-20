@extends('adminlte::page')

@section('title', 'Gerenciar Empresas')

@section('content_header')
<h1>Gerenciar Empresas</h1>
@endsection

@section('content')
@livewire('admin.companies.companies-manager')
@endsection

@section('css')
<style>
    .table th {
        cursor: pointer;
    }

    .table th:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection