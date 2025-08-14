@extends('adminlte::page')

@section('title', 'Nova Empresa')

@section('content_header')
    <i class="fas fa-building m-2"></i><span class="text-lg">Formulário</span>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('empresas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                        class="form-control @error('nome') is-invalid @enderror" required>
                    @error('nome')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="documento">CPF / CNPJ</label>
                    <input type="text" id="documento" name="documento" value="{{ old('documento') }}"
                        class="form-control @error('documento') is-invalid @enderror" maxlength="18"
                        oninput="maskCpfCnpj(this)" required>
                    @error('documento')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email_contato">E-mail</label>
                    <input type="email" id="email_contato" name="email_contato" value="{{ old('email_contato') }}"
                        class="form-control @error('email_contato') is-invalid @enderror">
                    @error('email_contato')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
                        class="form-control @error('telefone') is-invalid @enderror">
                    @error('telefone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        function maskCpfCnpj(input) {
            let v = input.value.replace(/\D/g, '');

            if (v.length <= 11) {
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                v = v.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }

            input.value = v;
        }
    </script>
@stop
