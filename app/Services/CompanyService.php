<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyService
{
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);
        return $company;
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }

    public function find(int $id): ?Company
    {
        return Company::find($id);
    }

    public function findOrFail(int $id): Company
    {
        return Company::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Company::orderBy('name')->get();
    }

    public function search(string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Company::query()
            ->withCount('users')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('document', 'like', '%' . $search . '%')
            ->orWhere('contact_email', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getWithUsers(int $perPage = 10): LengthAwarePaginator
    {
        return Company::query()
            ->withCount('users')
            ->orderBy('name')
            ->paginate($perPage);
    }
}
