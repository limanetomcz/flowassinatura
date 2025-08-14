@extends('adminlte::page')

@section('title', 'Empresas')

@section('content_header')
    <i class="fas fa-building m-2"></i><span class="text-lg">Empresas</span>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('empresas.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nova Empresa
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th style="width: 15%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->id }}</td>
                            <td>{{ $empresa->nome }}</td>
                            <td>{{ $empresa->documento }}</td>
                            <td>{{ $empresa->email_contato }}</td>
                            <td>{{ $empresa->telefone }}</td>
                            <td>
                                <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta empresa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
