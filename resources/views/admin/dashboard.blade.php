{{-- -----------------------------------------------------------------------------
     resources/views/admin/dashboard.blade.php
------------------------------------------------------------------------------- --}}

@extends('adminlte::page')

{{-- ======================================
     Page Title
     (Title displayed in the browser tab)
====================================== --}}
@section('title', 'Painel Administrativo')

{{-- ======================================
     Page Header
     (Header section of the dashboard)
====================================== --}}
@section('content_header')
<h1 class="text-dark font-weight-bold">Painel Administrativo</h1>
@endsection

{{-- ======================================
     Main Content
     (Main dashboard content with cards and metrics)
====================================== --}}
@section('content')
<p class="mb-4 text-gray-700">
    Bem-vindo(a), <strong class="text-gray-900">{{ auth()->user()->name }}</strong>! Você está na área administrativa.
</p>

<div class="row">

    {{-- ----------------------------------------------------------------------
         Card: Companies
         - Link to manage all companies
         - Includes icon and gradient background
    ---------------------------------------------------------------------- --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="animated-box bg-gradient-menu-companies shadow-lg">
            <div class="inner">
                <h3>Empresas</h3>
                <p>Gerenciar empresas</p>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('admin.companies.index') }}" class="animated-footer">
                Acessar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- ----------------------------------------------------------------------
         Card: Documents
         - Link to manage documents and signatures
    ---------------------------------------------------------------------- --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="animated-box bg-gradient-menu-documents shadow-lg">
            <div class="inner">
                <h3>Documentos</h3>
                <p>Gerenciar documentos e assinaturas</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ route('admin.documents.index') }}" class="animated-footer">
                Acessar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- ----------------------------------------------------------------------
         Card: Reports
         - Link to view system reports and statistics
    ---------------------------------------------------------------------- --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="animated-box bg-gradient-menu-reports shadow-lg">
            <div class="inner">
                <h3>Relatórios</h3>
                <p>Visualizar estatísticas</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="#" class="animated-footer">
                Acessar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- ----------------------------------------------------------------------
         Card: Settings
         - Link to system settings and preferences
    ---------------------------------------------------------------------- --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="animated-box bg-gradient-menu-settings shadow-lg">
            <div class="inner">
                <h3>Configurações</h3>
                <p>Preferências do sistema</p>
            </div>
            <div class="icon"><i class="fas fa-cogs"></i></div>
            <a href="#" class="animated-footer">
                Acessar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

{{-- ----------------------------------------------------------------------
     General Summary Section
     - Displays key metrics like total users, recent logins,
       signed documents, and pending signatures
----------------------------------------------------------------------- --}}
<div class="card mt-5 shadow-lg rounded-lg border-0">
    <div class="card-header bg-gradient-gray-light">
        <h3 class="card-title font-weight-bold">Resumo Geral</h3>
    </div>
    <div class="card-body">
        <div class="row text-center">

            {{-- Metric: Total Users --}}
            <div class="col-md-3 col-6 mb-4">
                <div class="metric-box bg-gradient-summary-users shadow">
                    <h2 class="counter" data-target="{{ $totalUsers ?? 0 }}">0</h2>
                    <p>Usuários</p>
                </div>
            </div>

            {{-- Metric: Recent Logins --}}
            <div class="col-md-3 col-6 mb-4">
                <div class="metric-box bg-gradient-summary-logins shadow">
                    <h2 class="counter" data-target="{{ $recentLogins ?? 0 }}">0</h2>
                    <p>Logins Recentes</p>
                </div>
            </div>

            {{-- Metric: Signed Documents --}}
            <div class="col-md-3 col-6 mb-4">
                <div class="metric-box bg-gradient-summary-signed shadow">
                    <h2 class="counter" data-target="{{ $signedDocuments ?? 0 }}">0</h2>
                    <p>Documentos Assinados</p>
                </div>
            </div>

            {{-- Metric: Pending Signatures --}}
            <div class="col-md-3 col-6 mb-4">
                <div class="metric-box bg-gradient-summary-pending shadow">
                    <h2 class="counter" data-target="{{ $pendingSignatures ?? 0 }}">0</h2>
                    <p>Assinaturas Pendentes</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

{{-- ======================================
     External CSS
     (Custom dashboard styles)
====================================== --}}
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

{{-- ======================================
     External JS
     (Custom dashboard scripts)
====================================== --}}
@section('js')
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection
