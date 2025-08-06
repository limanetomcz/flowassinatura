{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Painel Administrativo')

@section('content_header')
    <h1>Painel Administrativo</h1>
@endsection

@section('content')
    <p>Bem-vindo(a), <strong>{{ auth()->user()->name }}</strong>! Você está na área administrativa.</p>

    <div class="row mt-4">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>Usuários</h3>
                    <p>Gerenciar usuários</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Acessar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Adicione mais "small-box" para estatísticas e links rápidos --}}
    </div>
@endsection

@section('css')
    {{-- Adicione CSS personalizado se necessário --}}
    <style>
        /* Exemplo */
        .small-box { transition: 0.3s; }
        .small-box:hover { transform: scale(1.02); }
    </style>
@endsection

@section('js')
    {{-- Scripts adicionais, se necessário --}}
    <script>
        console.log('Painel admin carregado!');
    </script>
@endsection
