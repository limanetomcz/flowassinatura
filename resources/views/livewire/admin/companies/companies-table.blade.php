<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Gerenciar Empresas</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nova Empresa
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Barra de Pesquisa -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input wire:model.live="search" type="text" class="form-control" placeholder="Pesquisar empresas...">
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
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                Nome
                                @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('document')" style="cursor: pointer;">
                                Documento
                                @if($sortField === 'document')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('contact_email')" style="cursor: pointer;">
                                Email
                                @if($sortField === 'contact_email')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Telefone</th>
                            <th>Usuários</th>
                            <th width="150">Ações</th>
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
                                    <span class="badge badge-info">{{ $company->users_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.companies.edit', $company->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                                                                 <button wire:click="deleteCompany({{ $company->id }})" 
                                                 class="btn btn-sm btn-danger" 
                                                 title="Excluir"
                                                 wire:confirm="Tem certeza que deseja excluir esta empresa?">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <p class="text-muted">Nenhuma empresa encontrada.</p>
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
