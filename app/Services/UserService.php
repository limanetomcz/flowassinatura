<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        // Hash da senha se fornecida
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        // Hash da senha se fornecida e não estiver vazia
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Remove senha vazia
        }

        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return User::with('company')->orderBy('name')->get();
    }

    public function search(string $search, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->with('company')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getWithCompany(int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->with('company')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getAdmins(): Collection
    {
        return User::where('is_admin', true)->orderBy('name')->get();
    }

    public function getByCompany(int $companyId): Collection
    {
        return User::where('company_id', $companyId)->orderBy('name')->get();
    }
}
