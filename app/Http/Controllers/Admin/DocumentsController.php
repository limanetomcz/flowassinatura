<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Company;
use App\Services\DocumentsService;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * DocumentsController
 * -----------------------------------------------------------------------------
 * Handles all administrative actions related to documents in the admin panel.
 *
 * Responsibilities:
 *   • List, search, and paginate documents
 *   • Filter documents by status
 *   • Create, edit, update, delete documents
 *   • Display document details with related entities (company, user, signatures)
 *
 * Access:
 *   • Restricted to authenticated admin users
 *
 * Example Usage:
 *   Route::resource('admin/documents', DocumentsController::class);
 *
 * @package App\Http\Controllers\Admin
 * =============================================================================
 */
class DocumentsController extends Controller
{
    /**
     * @var DocumentsService Service layer handling document operations.
     */
    protected DocumentsService $service;

    /**
     * Constructor with dependency injection of DocumentsService.
     *
     * @param DocumentsService $service
     * @return void
     */
    public function __construct(DocumentsService $service)
    {
        $this->service = $service;
    }

    /**
     * =============================================================================
     * Index
     * -----------------------------------------------------------------------------
     * Display a paginated list of documents with optional search and status filter.
     *
     * Features:
     *   • Loads relations: company and user
     *   • Filters by query string: 'q' (search) and 'status'
     *   • Orders by newest created first
     *
     * @param Request $request HTTP request containing optional query parameters.
     * @return \Illuminate\View\View Rendered documents index view.
     * =============================================================================
     */
    public function index(Request $request)
    {
        $search = $request->query('q', null);
        $status = $request->query('status', 'all');

        $documents = $this->service->paginate($search, $status);

        return view('admin.documents.index', compact('documents', 'search', 'status'));
    }

    /**
     * =============================================================================
     * Create
     * -----------------------------------------------------------------------------
     * Show the form to create a new document. Only the authenticated user's company
     * is available for selection.
     *
     * @return \Illuminate\View\View Document creation form view.
     * =============================================================================
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $companies = Company::where('id', $user->company_id)->get();

        return view('admin.documents.create', compact('companies'));
    }

    /**
     * =============================================================================
     * Store
     * -----------------------------------------------------------------------------
     * Store a newly created document in the database.
     *
     * Workflow:
     *   1. Validate request input
     *   2. Ensure company_id matches the authenticated user's company
     *   3. Handle PDF file upload via DocumentsService
     *   4. Redirect to documents index with success message
     *
     * @param Request $request HTTP request containing the new document data.
     * @return \Illuminate\Http\RedirectResponse Redirect to documents index.
     * =============================================================================
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:pdf|max:10240',
            'company_id' => 'required|in:' . $user->company_id,
            'status' => 'required|in:pending,signed,rejected',
        ]);

        $this->service->create($validated, $request->file('file_path'));

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Documento criado com sucesso!');
    }

    /**
     * =============================================================================
     * Edit
     * -----------------------------------------------------------------------------
     * Show the form to edit an existing document. Only the authenticated user's
     * company is available for selection.
     *
     * @param Document $document Document instance to edit.
     * @return \Illuminate\View\View Document edit form view.
     * =============================================================================
     */
    public function edit(Document $document)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $companies = Company::where('id', $user->company_id)->get();

        return view('admin.documents.edit', compact('document', 'companies'));
    }

    /**
     * =============================================================================
     * Update
     * -----------------------------------------------------------------------------
     * Update an existing document in the database.
     *
     * Workflow:
     *   1. Validate request input
     *   2. Ensure company_id matches the authenticated user's company
     *   3. Replace PDF file if a new file is uploaded
     *   4. Update document record via DocumentsService
     *   5. Redirect to documents index with success message
     *
     * @param Request $request HTTP request containing updated document data.
     * @param Document $document Document instance to update.
     * @return \Illuminate\Http\RedirectResponse Redirect to documents index.
     * =============================================================================
     */
    public function update(Request $request, Document $document)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
            'company_id' => 'required|in:' . $user->company_id,
            'status' => 'required|in:pending,signed,rejected',
        ]);

        $this->service->update($document, $validated, $request->file('file_path'));

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Documento atualizado com sucesso!');
    }

    /**
     * =============================================================================
     * Destroy
     * -----------------------------------------------------------------------------
     * Delete a document along with its associated file.
     *
     * @param Document $document Document instance to delete.
     * @return \Illuminate\Http\RedirectResponse Redirect to documents index.
     * =============================================================================
     */
    public function destroy(Document $document)
    {
        $this->service->delete($document);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Documento deletado com sucesso!');
    }

    /**
     * =============================================================================
     * Show
     * -----------------------------------------------------------------------------
     * Display detailed view of a document including its related entities.
     *
     * @param Document $document Document instance to show.
     * @return \Illuminate\View\View Document detail view.
     * =============================================================================
     */
    public function show(Document $document)
    {
        $document = $this->service->loadRelations($document);
        return view('admin.documents.show', compact('document'));
    }
}
