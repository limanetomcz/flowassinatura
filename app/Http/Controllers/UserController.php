<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserFormRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->userService->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->findOrFail($id);
            $user->load('company');

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não encontrado'
            ], 404);
        }
    }

    public function edit($id)
    {
        return view('admin.users.edit', compact('id'));
    }

    public function update(UserFormRequest $request, $id): JsonResponse
    {
        try {
            $user = $this->userService->findOrFail($id);
            $data = $request->validated();

            $updatedUser = $this->userService->update($user, $data);

            return response()->json([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso!',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = $this->userService->findOrFail($id);
            $this->userService->delete($user);

            return response()->json([
                'success' => true,
                'message' => 'Usuário excluído com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir usuário: ' . $e->getMessage()
            ], 500);
        }
    }
}
