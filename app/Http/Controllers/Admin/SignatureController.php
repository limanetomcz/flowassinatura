<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Signature;
use App\Services\SignatureService;

/**
 * =============================================================================
 * SignatureController
 * -----------------------------------------------------------------------------
 * Handles all administrative actions related to document signatures.
 *
 * Responsibilities:
 *   • List, create, update, delete signatures
 *   • Notify users about signatures
 *
 * Access:
 *   • Restricted to authenticated admin users
 *
 * @package App\Http\Controllers\Admin
 * =============================================================================
 */
class SignatureController extends Controller
{
    /**
     * @var SignatureService Service layer handling signature operations
     */
    protected SignatureService $service;

    /**
     * Constructor with dependency injection of SignatureService
     *
     * @param SignatureService $service
     */
    public function __construct(SignatureService $service)
    {
        $this->service = $service;
    }

    /**
     * =============================================================================
     * Index
     * -----------------------------------------------------------------------------
     * List all signatures for a specific document.
     *
     * Workflow:
     *   1. Retrieve the document by ID
     *   2. Retrieve all signatures for this document
     *   3. Pass data to Blade view
     *
     * @param int $documentId Document ID
     * @return \Illuminate\View\View
     * =============================================================================
     */
    public function index(int $documentId)
    {
        $document = Document::findOrFail($documentId);
        $signatures = $this->service->list($document);

        return view('admin.documents.signatures.index', compact('document', 'signatures'));
    }

    /**
     * =============================================================================
     * Show
     * -----------------------------------------------------------------------------
     * Show details of a specific signature.
     *
     * @param int $documentId Document ID
     * @param int $signatureId Signature ID
     * @return \Illuminate\View\View
     * =============================================================================
     */
    public function show(int $documentId, int $signatureId)
    {
        $signature = $this->service->find($documentId, $signatureId);
        return view('admin.documents.signatures.show', compact('signature'));
    }

    /**
     * =============================================================================
     * Edit
     * -----------------------------------------------------------------------------
     * Show the edit form for a signature.
     *
     * @param int $documentId Document ID
     * @param int $signatureId Signature ID
     * @return \Illuminate\View\View
     * =============================================================================
     */
    public function edit(int $documentId, int $signatureId)
    {
        $document = Document::findOrFail($documentId);
        $editSignature = $this->service->find($documentId, $signatureId);

        return view('admin.documents.signatures.index', compact('document', 'editSignature'));
    }

    /**
     * =============================================================================
     * Store
     * -----------------------------------------------------------------------------
     * Store a new signature for a document.
     *
     * Workflow:
     *   1. Validate the request input
     *   2. Retrieve the document
     *   3. Create the signature via SignatureService
     *   4. Redirect to signatures index with success or error message
     *
     * @param Request $request HTTP request containing user_id
     * @param int $documentId Document ID
     * @return \Illuminate\Http\RedirectResponse
     * =============================================================================
     */
    public function store(Request $request, int $documentId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $document = Document::findOrFail($documentId);

        try {
            $this->service->create($document, $request->user_id);
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('message', 'Signature added successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * =============================================================================
     * Update
     * -----------------------------------------------------------------------------
     * Update an existing signature.
     *
     * Workflow:
     *   1. Validate the request input
     *   2. Retrieve the signature
     *   3. Update via SignatureService
     *   4. Redirect to signatures index or edit with success/error message
     *
     * @param Request $request HTTP request containing user_id
     * @param int $documentId Document ID
     * @param int $signatureId Signature ID
     * @return \Illuminate\Http\RedirectResponse
     * =============================================================================
     */
    public function update(Request $request, int $documentId, int $signatureId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $signature = $this->service->find($documentId, $signatureId);

        try {
            $this->service->update($signature, $request->user_id);
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('message', 'Signature updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.documents.signatures.edit', [$documentId, $signatureId])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * =============================================================================
     * Destroy
     * -----------------------------------------------------------------------------
     * Delete a signature.
     *
     * Workflow:
     *   1. Retrieve the signature
     *   2. Delete via SignatureService
     *   3. Redirect to signatures index with success/error message
     *
     * @param int $documentId Document ID
     * @param int $signatureId Signature ID
     * @return \Illuminate\Http\RedirectResponse
     * =============================================================================
     */
    public function destroy(int $documentId, int $signatureId)
    {
        $signature = $this->service->find($documentId, $signatureId);

        try {
            $this->service->delete($signature);
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('message', 'Signature removed successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * =============================================================================
     * Notify
     * -----------------------------------------------------------------------------
     * Notify a user about a signature.
     *
     * Workflow:
     *   1. Retrieve the signature
     *   2. Notify user via SignatureService
     *   3. Redirect to signatures index with success/error message
     *
     * @param int $documentId Document ID
     * @param int $signatureId Signature ID
     * @return \Illuminate\Http\RedirectResponse
     * =============================================================================
     */
    public function notify(int $documentId, int $signatureId)
    {
        $signature = $this->service->find($documentId, $signatureId);

        try {
            $this->service->notify($signature);
            $user = $signature->user;
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('message', "Notification sent to user {$user->name} for signature #{$signature->id}.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.documents.signatures.index', $documentId)
                ->with('error', $e->getMessage());
        }
    }
}
