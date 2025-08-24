<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

/**
 * =============================================================================
 * DocumentsService
 * -----------------------------------------------------------------------------
 * Handles all business logic related to document management in the admin panel.
 *
 * Responsibilities:
 *   • Paginate and search documents (only for user's company)
 *   • Filter documents by status
 *   • Create new documents with file uploads (linked to user's company)
 *   • Update existing documents and replace files
 *   • Delete documents and associated files
 *   • Load documents with related entities
 *
 * Usage Example:
 *   $documents = app(DocumentsService::class)->paginate('contract', 'pending', 10);
 *
 * @package App\Services
 * =============================================================================
 */
class DocumentsService
{
    /**
     * =============================================================================
     * Paginate Documents
     * -----------------------------------------------------------------------------
     * Paginate documents with optional search and status filter.
     * Only documents belonging to the authenticated user's company are returned.
     *
     * Features:
     *   • Includes relations: company, user
     *   • Supports search by document title or company name
     *   • Filters by status if specified
     *   • Orders by newest created first
     *
     * @param string|null $searchTerm Optional search term to filter by title or company name
     * @param string $status Optional status filter ('all', 'pending', 'signed', 'rejected')
     * @param int $perPage Number of items per page (default 9)
     * @return LengthAwarePaginator Paginated list of documents
     * =============================================================================
     */
    public function paginate(?string $searchTerm = null, string $status = 'all', int $perPage = 9): LengthAwarePaginator
    {
        $user = Auth::user();

        $query = Document::with(['company', 'user'])
                         ->where('company_id', $user->company_id)
                         ->orderBy('created_at', 'desc');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhereHas('company', function ($q2) use ($searchTerm) {
                      $q2->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * =============================================================================
     * Create Document
     * -----------------------------------------------------------------------------
     * Creates a new document and stores an uploaded file if provided.
     * Automatically links the document to the authenticated user's company and user.
     *
     * Workflow:
     *   1. Assign authenticated user's company_id and user_id
     *   2. Store uploaded file to 'public/documents' if provided
     *   3. Persist document in database
     *
     * @param array $data Document data (title, status, etc.)
     * @param UploadedFile|null $file Optional uploaded file (PDF)
     * @return Document Newly created document instance
     * =============================================================================
     */
    public function create(array $data, ?UploadedFile $file = null): Document
    {
        $user = Auth::user();

        $data['company_id'] = $user->company_id;
        $data['user_id'] = $user->id;

        if ($file) {
            $data['file_path'] = $file->store('documents', 'public');
        }

        return Document::create($data);
    }

    /**
     * =============================================================================
     * Update Document
     * -----------------------------------------------------------------------------
     * Updates an existing document and optionally replaces its associated file.
     * Ensures the document belongs to the authenticated user's company.
     *
     * Workflow:
     *   1. Verify ownership of the document
     *   2. Delete previous file if new file is uploaded
     *   3. Store new file
     *   4. Update document record
     *
     * @param Document $document Document instance to update
     * @param array $data Updated document data
     * @param UploadedFile|null $file Optional uploaded file (PDF)
     * @return Document Updated document instance
     * @throws Exception If document does not belong to authenticated user's company
     * =============================================================================
     */
    public function update(Document $document, array $data, ?UploadedFile $file = null): Document
    {
        $user = Auth::user();

        if ($document->company_id !== $user->company_id) {
            throw new Exception('Você não pode atualizar documentos de outra empresa.');
        }

        if ($file) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $file->store('documents', 'public');
        }

        $document->update($data);

        return $document;
    }

    /**
     * =============================================================================
     * Delete Document
     * -----------------------------------------------------------------------------
     * Deletes a document along with its associated file from storage.
     * Ensures the document belongs to the authenticated user's company.
     *
     * Workflow:
     *   1. Verify ownership of the document
     *   2. Delete file from storage if exists
     *   3. Delete document record
     *
     * @param Document $document Document instance to delete
     * @return void
     * @throws Exception If document does not belong to authenticated user's company
     * =============================================================================
     */
    public function delete(Document $document): void
    {
        $user = Auth::user();

        if ($document->company_id !== $user->company_id) {
            throw new Exception('Você não pode deletar documentos de outra empresa.');
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
    }

    /**
     * =============================================================================
     * Load Relations
     * -----------------------------------------------------------------------------
     * Loads related entities for a document, including:
     *   • company
     *   • user (creator)
     *   • signatures with users
     *
     * @param Document $document Document instance to load relations
     * @return Document Document instance with loaded relations
     * =============================================================================
     */
    public function loadRelations(Document $document): Document
    {
        return $document->load(['company', 'user', 'signatures.user']);
    }
}
