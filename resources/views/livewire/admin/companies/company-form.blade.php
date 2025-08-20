<div>
    <form wire:submit.prevent="save">
        <div class="card-body">
            <div class="row">
                <!-- Nome da Empresa -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nome da Empresa *</label>
                        <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="Digite o nome da empresa">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Documento -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="document">Documento (CNPJ/CPF) *</label>
                        <input wire:model="document" type="text"
                            class="form-control @error('document') is-invalid @enderror" id="document"
                            placeholder="Digite o documento">
                        @error('document')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Email de Contato -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact_email">Email de Contato *</label>
                        <input wire:model="contact_email" type="email"
                            class="form-control @error('contact_email') is-invalid @enderror" id="contact_email"
                            placeholder="Digite o email de contato">
                        @error('contact_email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Número de Contato -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact_number">Telefone de Contato *</label>
                        <input wire:model="contact_number" type="text"
                            class="form-control @error('contact_number') is-invalid @enderror" id="contact_number"
                            placeholder="Digite o telefone de contato">
                        @error('contact_number')
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