{{-- Painel Administrativo Principal --}}

@extends('adminlte::page')

@section('title', 'Painel Administrativo')

{{-- Cabeçalho da página --}}
@section('content_header')
    <h1>Painel Administrativo</h1>
@endsection

{{-- Conteúdo principal da dashboard --}}
@section('content')
    {{-- Saudação personalizada com nome do usuário autenticado --}}
    <p>Bem-vindo(a), <strong>{{ auth()->user()->name }}</strong>! Você está na área administrativa.</p>

    {{-- Seção de Ações Rápidas com estatísticas ou atalhos --}}
    <div class="row mt-4">

        {{-- Card: Gerenciar Usuários --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>Usuários</h3>
                    <p>Gerenciar usuários</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer" aria-label="Gerenciar usuários">
                    Acessar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Card: Relatórios --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Relatórios</h3>
                    <p>Visualizar estatísticas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="small-box-footer" aria-label="Visualizar relatórios">
                    Acessar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Card: Configurações --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Configurações</h3>
                    <p>Preferências do sistema</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <a href="{{ route('admin.settings.index') }}" class="small-box-footer" aria-label="Ir para configurações">
                    Acessar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Card: Suporte ou mensagens --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Mensagens</h3>
                    <p>Contatos e suporte</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="small-box-footer" aria-label="Ver mensagens">
                    Acessar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Seção de métricas ou tabelas (exemplo futuro) --}}
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Resumo Geral</h3>
        </div>
        <div class="card-body">
            {{-- Substitua com conteúdo real, como tabelas, gráficos ou indicadores --}}
            <p>Em breve: aqui você poderá visualizar métricas detalhadas do sistema, como total de usuários, logins recentes, etc.</p>
        </div>
    </div>
@endsection

{{-- Estilos personalizados para o painel --}}
@section('css')
    <style>
        /* Efeito hover suave nas caixas */
        .small-box {
            transition: transform 0.2s ease-in-out;
        }
        .small-box:hover {
            transform: scale(1.03);
        }

        /* Ajuste opcional para ícones */
        .small-box .icon {
            top: 10px;
        }
    </style>
@endsection

{{-- Scripts opcionais para interação dinâmica --}}
@section('js')
    <script>
        // Apenas para demonstração/log durante desenvolvimento
        console.log('Painel administrativo carregado para {{ auth()->user()->email }}');
    </script>
@endsection
