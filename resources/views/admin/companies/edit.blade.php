@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content_header')
<h1>Editar Empresa</h1>
@endsection

@section('content')
@livewire('admin.companies.company-form', ['companyId' => $id])
@endsection