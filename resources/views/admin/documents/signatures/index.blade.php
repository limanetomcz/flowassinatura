{{-- =============================================================================
     Admin Dashboard - Document Signatures Page
=============================================================================
     This Blade template renders the "Signatures" page for a specific document
     in the admin panel. It provides an interface for adding, editing, viewing,
     notifying, and deleting signatures associated with the document.

     Features:
       • Page header with document ID and navigation
       • Flash messages for user feedback
       • Dynamic form for creating or editing a signature
       • List of all signatures for the document
       • Action buttons: edit, view, notify, delete
       • Tooltip support for buttons
       • Responsive layout and card-based UI
============================================================================= --}}

@extends('adminlte::page')

@section('title', 'Assinaturas do Documento #' . $document->id)

{{-- =============================================================================
     1. Page Header Section
=============================================================================
     Displays:
       • Page title with document ID
       • Icon for visual identification
       • Back button to return to documents index
============================================================================= --}}
@section('content_header')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
    <h1 class="m-0 text-dark mb-3">
        <i class="fas fa-file-signature text-primary me-2"></i>
        Assinaturas do Documento #{{ $document->id }}
    </h1>

    <div class="d-flex flex-column gap-2">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary shadow-sm d-flex align-items-center">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>
@endsection

{{-- =============================================================================
     2. Main Content Section
=============================================================================
     Contains flash messages, the dynamic signature form, and the list of 
     existing signatures.
============================================================================= --}}
@section('content')

{{-- -------------------------
     2.1 Flash Message
--------------------------
     Displays success messages after form submission or actions.
------------------------- --}}
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@php
    $editing = isset($editSignature) && $editSignature instanceof \App\Models\Signature;
@endphp

{{-- -------------------------
     2.2 Signature Form
--------------------------
     Dynamic form for creating a new signature or editing an existing one.
     Highlights the card if editing, and switches routes & methods accordingly.
------------------------- --}}
<div class="card shadow-sm mb-4 {{ $editing ? 'border-warning border-3 shadow-glow' : '' }}">
    <div class="card-header fw-bold bg-white {{ $editing ? 'bg-warning bg-opacity-10' : '' }}">
        {{ $editing ? 'Editar Assinatura' : 'Adicionar Nova Assinatura' }}
    </div>
    <div class="card-body">
        <form action="{{ $editing 
            ? route('admin.documents.signatures.update', ['document' => $document->id, 'signature' => $editSignature->id]) 
            : route('admin.documents.signatures.store', $document->id) }}" 
            method="POST">
            
            @csrf
            @if($editing)
                @method('PUT')
            @endif

            {{-- ----------------------
                 2.2.1 User Selector
            ----------------------
                 Dropdown to select the user associated with the signature.
                 Preselects user if editing an existing signature.
            ---------------------- --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="user_id" class="form-label fw-bold">Usuário</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Selecione um usuário</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" 
                                {{ ($editing && $editSignature->user_id == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ----------------------
                 2.2.2 Form Actions
            ----------------------
                 Submit button changes text dynamically based on editing or adding.
                 Cancel button only appears when editing.
            ---------------------- --}}
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ $editing ? 'Atualizar Assinatura' : 'Adicionar Assinatura' }}
                </button>
                @if($editing)
                    <a href="{{ route('admin.documents.signatures.index', $document->id) }}" 
                       class="btn btn-secondary">Cancelar</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- -------------------------
     2.3 Signatures List
--------------------------
     Displays all signatures for the document as cards with:
       • User name & ID
       • Signature hash
       • Signed timestamp
       • Action buttons: edit, view, notify, delete
------------------------- --}}
<div class="row mt-3">
    @forelse($document->signatures as $signature)
    <div class="col-12 mb-3 d-flex signature-card">
        <div class="card shadow-sm flex-fill bg-gradient-light border-left-primary w-100">
            
            {{-- Card Header --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-0">
                <span class="fw-bold text-dark">
                    <i class="fas fa-user-check me-2 text-secondary"></i>
                    {{ $signature->user->name ?? 'Usuário #' . $signature->user_id }}
                </span>
                <span class="text-muted">{{ $signature->signed_at?->format('d/m/Y H:i') ?? '-' }}</span>
            </div>

            {{-- Card Body --}}
            <div class="card-body d-flex justify-content-between text-dark flex-wrap align-items-center">
                <div>
                    <p class="mb-0">
                        <strong>Hash da assinatura:</strong> {{ Str::limit($signature->signature_hash, 50) }}
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-2 align-items-center mt-2 mt-md-0">
                    
                    {{-- Edit --}}
                    <a href="{{ route('admin.documents.signatures.edit', ['document' => $document->id, 'signature' => $signature->id]) }}" 
                       class="btn btn-info btn-sm" title="Editar Assinatura" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- View Details --}}
                    <a href="{{ route('admin.documents.signatures.show', ['document' => $document->id, 'signature' => $signature->id]) }}" 
                       class="btn btn-secondary btn-sm" title="Visualizar Detalhes" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="fas fa-eye"></i>
                    </a>

                    {{-- Notify --}}
                    <form action="{{ route('admin.documents.signatures.notify', ['document' => $document->id, 'signature' => $signature->id]) }}" 
                          method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" 
                                title="Enviar Notificação" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-bell"></i>
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('admin.documents.signatures.destroy', ['document' => $document->id, 'signature' => $signature->id]) }}" 
                          method="POST" 
                          onsubmit="return confirm('Tem certeza que deseja excluir esta assinatura?')" 
                          class="m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                title="Excluir Assinatura" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
    @empty
    {{-- No Signatures Found --}}
    <div class="col-12 text-center text-muted py-3">
        Nenhuma assinatura encontrada.
    </div>
    @endforelse
</div>

@endsection

{{-- =============================================================================
     3. External Styles & Scripts
=============================================================================
     Custom CSS and JS for this page. Includes:
       • Highlight glow effect for edit forms
============================================================================= --}}
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/signatures.css') }}">

<style>
/* Shadow glow for edit form card */
.shadow-glow {
    box-shadow: 0 0 15px 0 rgba(255, 193, 7, 0.5);
    transition: box-shadow 0.5s ease-in-out;
}
.shadow-glow:hover {
    box-shadow: 0 0 25px 5px rgba(255, 193, 7, 0.7);
}
</style>
@endsection

@section('js')
<script src="{{ asset('js/admin/signatures.js') }}"></script>
@endsection
