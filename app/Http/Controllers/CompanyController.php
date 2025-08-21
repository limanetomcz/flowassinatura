<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyFormRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        return view('admin.companies.index');
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(CompanyFormRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $company = $this->companyService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Empresa criada com sucesso!',
                'data' => $company
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $company = $this->companyService->findOrFail($id);
            $company->load('users');
            
            return response()->json([
                'success' => true,
                'data' => $company
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa não encontrada'
            ], 404);
        }
    }

    public function edit($id)
    {
        return view('admin.companies.edit', compact('id'));
    }

    public function update(CompanyFormRequest $request, $id): JsonResponse
    {
        try {
            $company = $this->companyService->findOrFail($id);
            $data = $request->validated();
            
            $updatedCompany = $this->companyService->update($company, $data);
            
            return response()->json([
                'success' => true,
                'message' => 'Empresa atualizada com sucesso!',
                'data' => $updatedCompany
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $company = $this->companyService->findOrFail($id);
            $this->companyService->delete($company);
            
            return response()->json([
                'success' => true,
                'message' => 'Empresa excluída com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir empresa: ' . $e->getMessage()
            ], 500);
        }
    }
}
