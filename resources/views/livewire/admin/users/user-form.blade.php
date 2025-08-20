<div>
    <form wire:submit.prevent="save">
        <div class="card-body">
            <div class="row">
                <!-- Nome do Usuário -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nome do Usuário *</label>
                        <input wire:model="name" 
                               type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               placeholder="Digite o nome do usuário">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input wire:model="email" 
                               type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               placeholder="Digite o email">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Senha -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">
                            Senha {{ $isEditing ? '' : '*' }}
                            @if($isEditing)
                                <small class="text-muted">(deixe em branco para manter a atual)</small>
                            @endif
                        </label>
                        <input wire:model="password" 
                               type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               placeholder="{{ $isEditing ? 'Nova senha (opcional)' : 'Digite a senha' }}">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Confirmação de Senha -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">
                            Confirmar Senha {{ $isEditing ? '' : '*' }}
                        </label>
                        <input wire:model="password_confirmation" 
                               type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               placeholder="{{ $isEditing ? 'Confirme a nova senha' : 'Confirme a senha' }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Empresa -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_id">Empresa</label>
                        <select wire:model="company_id" 
                                class="form-control @error('company_id') is-invalid @enderror" 
                                id="company_id">
                            <option value="">Selecione uma empresa</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Admin -->
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input wire:model="is_admin" 
                                   type="checkbox" 
                                   class="custom-control-input @error('is_admin') is-invalid @enderror" 
                                   id="is_admin">
                            <label class="custom-control-label" for="is_admin">
                                Usuário Administrador
                            </label>
                        </div>
                        @error('is_admin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
