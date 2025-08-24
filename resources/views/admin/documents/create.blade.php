@extends('adminlte::page')

@section('title', 'Criar Documento')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="m-0 text-dark">
        <i class="fas fa-file-upload mr-2 text-primary"></i> Criar Documento
    </h1>
    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left mr-1"></i> Voltar
    </a>
</div>
@endsection

@section('content')
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Create Document Form --}}
        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" id="document-form">
            @csrf

            {{-- Title Field --}}
            <div class="form-group">
                <label for="title">Título</label>
                <div class="input-group shadow-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                    </div>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="{{ old('title') }}" placeholder="Digite o título do documento" required>
                </div>
            </div>

            {{-- Company Selector (User's Company Only) --}}
            <div class="form-group">
                <label for="company_id">Empresa</label>
                <div class="input-group shadow-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                    </div>
                    <select name="company_id" id="company_id" class="form-control" required disabled>
                        <option value="{{ Auth::user()->company->id }}" selected>
                            {{ Auth::user()->company->name }}
                        </option>
                    </select>
                    <input type="hidden" name="company_id" value="{{ Auth::user()->company->id }}">
                </div>
            </div>

            {{-- Status Selector --}}
            <div class="form-group">
                <label for="status">Status</label>
                <div class="input-group shadow-sm">
                    <div class="input-group-prepend" id="status-icon">
                        <span class="input-group-text"><i class="fas fa-info-circle text-warning"></i></span>
                    </div>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" data-icon="fas fa-hourglass-half" data-color="text-warning"
                            {{ (old('status') == 'pending') ? 'selected' : '' }}>Pendente</option>
                        <option value="signed" data-icon="fas fa-check-circle" data-color="text-success"
                            {{ (old('status') == 'signed') ? 'selected' : '' }}>Assinado</option>
                        <option value="rejected" data-icon="fas fa-times-circle" data-color="text-danger"
                            {{ (old('status') == 'rejected') ? 'selected' : '' }}>Rejeitado</option>
                    </select>
                </div>
            </div>

            {{-- PDF Upload Field --}}
            <div class="form-group">
                <label for="file_path">Arquivo PDF</label>
                <div id="drop-zone" class="border-dashed shadow-sm p-4 text-center rounded">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                    <p class="mb-0 text-muted">Arraste o PDF aqui ou clique para selecionar</p>
                    <span id="file-name" class="d-block mt-2 font-weight-medium animate__animated">
                        {{ old('file_path') ? old('file_path') : '' }}
                    </span>
                </div>
                <input type="file" name="file_path" id="file_path" class="d-none" accept="application/pdf">
                <small class="text-muted">Somente arquivos PDF são permitidos.</small>
            </div>

            {{-- Form Actions --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success shadow-sm mr-2">
                    <i class="fas fa-plus mr-1"></i> Criar Documento
                </button>
                <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
            </div>
        </form>

    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/documents-create.css') }}">
@endsection

@section('js')
<script src="{{ asset('js/admin/documents-create.js') }}"></script>
@endsection
