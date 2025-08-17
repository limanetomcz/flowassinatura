<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('admin.companies.index');
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string',
            'contact_email' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $company = Company::create($data);

        return response()->json($company, 201);
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function edit($id)
    {
        return view('admin.companies.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string',
            'contact_email' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $company = Company::findOrFail($id);
        $company->update($data);

        return response()->json($company);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(null, 204);
    }
}
