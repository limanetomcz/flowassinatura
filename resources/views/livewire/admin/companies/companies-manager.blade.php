<div>
    <!-- Alertas -->
    <div x-data="{ show: false, message: '', type: 'success' }" x-show="show"
        x-on:show-alert.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
        class="alert alert-dismissible" :class="type === 'success' ? 'alert-success' : 'alert-danger'"
        style="display: none;">
        <button type="button" class="close" @click="show = false" aria-hidden="true">×</button>
        <span x-text="message"></span>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div x-data="{ show: false, companyName: '' }" x-show="show"
        x-on:show-delete-modal.window="show = true; companyName = $event.detail.companyName"
        x-on:hide-delete-modal.window="show = false" class="modal fade" :class="{ 'show': show, 'd-block': show }"
        tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true"
        style="display: none; z-index: 1050;" @click.self="show = false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" @click="show = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a empresa <strong x-text="companyName"></strong>?</p>
                    <p class="text-muted">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="show = false">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Backdrop separado -->
    <div x-data="{ show: false }" x-show="show" x-on:show-delete-modal.window="show = true"
        x-on:hide-delete-modal.window="show = false" class="modal-backdrop fade" :class="{ 'show': show }"
        style="z-index: 1040; pointer-events: none;"></div>



    <!-- Formulário (mostrado quando showForm = true) -->
    <div x-show="$wire.showForm" x-transition>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $editingCompanyId ? 'Editar Empresa' : 'Nova Empresa' }}
                </h3>
            </div>

            @livewire('admin.companies.company-form', ['companyId' => $editingCompanyId], key('company-form-' . ($editingCompanyId ?? 'new')))
        </div>
    </div>

    <!-- Tabela (mostrada quando showForm = false) -->
    <div x-show="!$wire.showForm" x-transition>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <i class="fas fa-building me-2"></i> Empresas Cadastradas
                    </div>
                    <div class="col-md-6 text-right">
                        <button wire:click="showCreateForm" class="btn btn-primary float-end">
                            <i class="fas fa-plus-circle"></i> Nova Empresa
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Barra de Pesquisa -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Pesquisar empresas...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <select wire:model.live="perPage" class="form-control" style="width: auto;">
                                <option value="10">10 por página</option>
                                <option value="25">25 por página</option>
                                <option value="50">50 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Nome
                                    @if ($sortField === 'name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('document')" style="cursor: pointer;">
                                    Documento
                                    @if ($sortField === 'document')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Usuários</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->document }}</td>
                                    <td>{{ $company->contact_email }}</td>
                                    <td>{{ $company->contact_number }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $company->users_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="showEditForm({{ $company->id }})"
                                            class="btn btn-sm btn-info me-1" title="Editar Empresa">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button
                                            wire:click="showDeleteModal({{ $company->id }}, '{{ $company->name }}')"
                                            class="btn btn-sm btn-danger" title="Excluir Empresa">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i> Nenhuma empresa encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Mostrando {{ $companies->firstItem() ?? 0 }} a {{ $companies->lastItem() ?? 0 }}
                        de {{ $companies->total() }} registros
                    </div>
                    <div>
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
