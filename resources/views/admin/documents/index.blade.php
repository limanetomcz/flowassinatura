{{-- =============================================================================
     Admin Dashboard - Documents Index Page
     =============================================================================
     This Blade template renders the documents management page in the admin panel.
     
     Sections:
       1. Page Header           -> Title and navigation buttons
       2. Main Content          -> Flash messages, search, filters, document cards, pagination
       3. CSS / JS Includes     -> Page-specific styles and scripts
============================================================================= --}}

@extends('adminlte::page')

@section('title', 'Documentos')

{{-- =============================================================================
     1. Page Header
     -----------------------------------------------------------------------------
     Contains:
       • Page title with icon
       • Navigation buttons (Back to Dashboard, Create New Document)
============================================================================= --}}
@section('content_header')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
    <h1 class="m-0 text-dark mb-3">
        <i class="fas fa-folder-open text-primary me-2"></i> Documentos
    </h1>

    {{-- Navigation Buttons --}}
    <div class="d-flex flex-column gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary shadow-sm d-flex align-items-center">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Criar Novo Documento
        </a>
    </div>
</div>
@endsection

{{-- =============================================================================
     2. Main Content
     -----------------------------------------------------------------------------
     Contains:
       • Flash success alerts
       • Search form
       • Status filter buttons
       • Document cards with info and actions
       • Pagination
============================================================================= --}}
@section('content')

{{-- -------------------------
     2.1 Success Alert
     -------------------------
     Displays flash messages if a document action succeeded
-------------------------- --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

{{-- -------------------------
     2.2 Search Form
     -------------------------
     Allows users to search documents by title or other fields
-------------------------- --}}
<form method="GET" action="{{ route('admin.documents.index') }}">
    <div class="row mt-4">
        <div class="col-12 col-md-6 mx-auto">
            <div class="input-group shadow-sm rounded overflow-hidden search-bar">
                <input type="text" id="document-search" name="q" class="form-control border-0 py-2 px-3" placeholder="Pesquisar documentos..." value="{{ request('q') }}">
                <button class="btn btn-primary px-3" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</form>

{{-- -------------------------
     2.3 Status Filter Buttons
     -------------------------
     Provides quick filter buttons for:
       • All
       • Pending
       • Signed
       • Rejected
-------------------------- --}}
<div class="row mt-4">
    <div class="col-12 d-flex justify-content-center flex-wrap gap-2">
        @php
            $currentStatus = request('status', 'all');
            $searchQuery = request('q', '');
        @endphp

        <a href="{{ route('admin.documents.index', ['status' => 'all', 'q' => $searchQuery]) }}"
           class="btn {{ $currentStatus === 'all' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Todos</a>

        <a href="{{ route('admin.documents.index', ['status' => 'pending', 'q' => $searchQuery]) }}"
           class="btn {{ $currentStatus === 'pending' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">Pendentes</a>

        <a href="{{ route('admin.documents.index', ['status' => 'signed', 'q' => $searchQuery]) }}"
           class="btn {{ $currentStatus === 'signed' ? 'btn-success' : 'btn-outline-success' }} btn-sm">Assinados</a>

        <a href="{{ route('admin.documents.index', ['status' => 'rejected', 'q' => $searchQuery]) }}"
           class="btn {{ $currentStatus === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">Rejeitados</a>
    </div>
</div>

{{-- -------------------------
     2.4 Document Cards
     -------------------------
     Loops through $documents and displays:
       • Document ID & Title
       • Company & Creator
       • Status with icons and badges
       • Action buttons (view, edit, signatures, delete)
       • Responsive layout for mobile & desktop
-------------------------- --}}
<div class="row mt-3" id="documents-row">
    @forelse($documents as $document)
        @php
            $cardClass = match($document->status) {
                'pending' => 'bg-gradient-pending',
                'signed' => 'bg-gradient-signed',
                'rejected' => 'bg-gradient-rejected',
                default => 'bg-gradient-secondary',
            };
            $borderClass = match($document->status) {
                'pending' => 'status-border-pending',
                'signed' => 'status-border-signed',
                'rejected' => 'status-border-rejected',
                default => 'status-border-secondary',
            };
        @endphp

        <div class="col-12 mb-3 d-flex document-card">
            <div class="card shadow-sm hover-card flex-fill {{ $cardClass }} {{ $borderClass }}">
                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0">
                    <span class="fw-bold text-dark">
                        <i class="fas fa-file-alt me-2 text-secondary"></i> #{{ $document->id }} - {{ $document->title }}
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="card-body d-flex flex-column justify-content-between text-dark">
                    <div>
                        {{-- Column Headers for Medium+ Screens --}}
                        <div class="row fw-bold border-bottom pb-2 mb-2 d-none d-md-flex">
                            <div class="col-md-3"><i class="fas fa-building me-1 text-muted"></i> Empresa</div>
                            <div class="col-md-3"><i class="fas fa-user me-1 text-muted"></i> Criador</div>
                            <div class="col-md-3"><i class="fas fa-info-circle me-1 text-muted"></i> Status</div>
                            <div class="col-md-3"><i class="fas fa-cogs me-1 text-muted"></i> Ações</div>
                        </div>

                        {{-- Document Info Row --}}
                        <div class="row align-items-center py-2">
                            <div class="col-md-3">
                                <span class="d-md-none fw-bold"><i class="fas fa-building me-1"></i> Empresa: </span>
                                {{ $document->company->name ?? '-' }}
                            </div>
                            <div class="col-md-3">
                                <span class="d-md-none fw-bold"><i class="fas fa-user me-1"></i> Criador: </span>
                                {{ $document->user->name ?? '-' }}
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <span class="d-md-none fw-bold"><i class="fas fa-info-circle me-1"></i> Status: </span>
                                @switch($document->status)
                                    @case('pending') 
                                        <i class="fas fa-hourglass-half text-warning me-2"></i>
                                        <span class="badge badge-warning px-2 py-1">Pendente</span> 
                                        @break
                                    @case('signed') 
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="badge badge-success px-2 py-1">Assinado</span> 
                                        @break
                                    @case('rejected') 
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <span class="badge badge-danger px-2 py-1">Rejeitado</span> 
                                        @break
                                    @default 
                                        <span class="badge badge-secondary px-2 py-1">-</span>
                                @endswitch
                            </div>
                            <div class="col-md-3 d-flex flex-wrap">
                                {{-- Action Buttons --}}
                                <a href="{{ route('admin.documents.show', $document->id) }}" class="btn btn-info btn-sm m-1" data-bs-toggle="tooltip" title="Visualizar Documento">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-warning btn-sm m-1" data-bs-toggle="tooltip" title="Editar Documento">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.documents.signatures.index', ['document' => $document->id]) }}" class="btn btn-primary btn-sm m-1" data-bs-toggle="tooltip" title="Visualizar Assinaturas">
                                    <i class="fas fa-file-signature"></i>
                                </a>
                                <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" class="m-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este documento?');" data-bs-toggle="tooltip" title="Excluir Documento">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{-- No Documents Found --}}
        <div class="col-12 text-center text-muted py-5">
            <i class="fas fa-exclamation-circle me-2"></i> Nenhum documento encontrado.
        </div>
    @endforelse
</div>

{{-- -------------------------
     2.5 Pagination
     -------------------------
     Displays Bootstrap 5 pagination links with preserved search and status filters
-------------------------- --}}
<div class="d-flex justify-content-center mt-4">
    {{ $documents->appends(['q' => $searchQuery, 'status' => $currentStatus])->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

@endsection

{{-- =============================================================================
     3. CSS / JS Includes
============================================================================= --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/documents.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/admin/documents.js') }}"></script>
@endsection
