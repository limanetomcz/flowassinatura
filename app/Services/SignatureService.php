<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Signature;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * =============================================================================
 * SignatureService
 * -----------------------------------------------------------------------------
 * Handles all business logic related to document signatures.
 *
 * Responsibilities:
 *   • List, create, update, delete signatures
 *   • Load signatures with related entities
 *   • Notify users about signatures
 *
 * Usage Example:
 *   $signatures = app(SignatureService::class)->list($document);
 *
 * @package App\Services
 * =============================================================================
 */
class SignatureService
{
    /**
     * =============================================================================
     * List Signatures
     * -----------------------------------------------------------------------------
     * Retrieves all signatures for a given document belonging to the user's company.
     *
     * @param Document $document Document instance
     * @return Collection Collection of Signature models
     * @throws Exception If the document does not belong to the user's company
     * =============================================================================
     */
    public function list(Document $document): Collection
    {
        $user = Auth::user();

        if ($document->company_id !== $user->company_id) {
            throw new Exception('Você não pode acessar assinaturas de documentos de outra empresa.');
        }

        return $document->signatures()->with('user')->get();
    }

    /**
     * =============================================================================
     * Find Signature
     * -----------------------------------------------------------------------------
     * Retrieves a specific signature and ensures it belongs to the user's company.
     *
     * @param int $documentId ID of the document
     * @param int $signatureId ID of the signature
     * @return Signature Signature instance
     * @throws Exception If the signature's document does not belong to the user's company
     * =============================================================================
     */
    public function find(int $documentId, int $signatureId): Signature
    {
        $signature = Signature::with('user', 'document')->findOrFail($signatureId);
        $user = Auth::user();

        if ($signature->document->company_id !== $user->company_id) {
            throw new Exception('Você não pode acessar esta assinatura.');
        }

        return $signature;
    }

    /**
     * =============================================================================
     * Create Signature
     * -----------------------------------------------------------------------------
     * Creates a new signature for a document, only if it belongs to the user's company.
     *
     * @param Document $document Document instance
     * @param int $userId ID of the user who signs
     * @return Signature Newly created Signature instance
     * @throws Exception If document does not belong to user's company or creation fails
     * =============================================================================
     */
    public function create(Document $document, int $userId): Signature
    {
        $user = Auth::user();

        if ($document->company_id !== $user->company_id) {
            throw new Exception('Você não pode criar assinatura para documentos de outra empresa.');
        }

        try {
            return $document->signatures()->create([
                'user_id' => $userId,
                'signed_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::error("Error creating signature for document {$document->id}: {$e->getMessage()}");
            throw new Exception('Falha ao criar assinatura.');
        }
    }

    /**
     * =============================================================================
     * Update Signature
     * -----------------------------------------------------------------------------
     * Updates the user of a signature, only if the document belongs to the user's company.
     *
     * @param Signature $signature Signature instance to update
     * @param int $userId New user ID for the signature
     * @return Signature Updated Signature instance
     * @throws Exception If the signature's document does not belong to user's company or update fails
     * =============================================================================
     */
    public function update(Signature $signature, int $userId): Signature
    {
        $user = Auth::user();

        if ($signature->document->company_id !== $user->company_id) {
            throw new Exception('Você não pode atualizar assinaturas de outra empresa.');
        }

        try {
            $signature->update(['user_id' => $userId]);
            return $signature;
        } catch (Exception $e) {
            Log::error("Error updating signature {$signature->id}: {$e->getMessage()}");
            throw new Exception('Falha ao atualizar assinatura.');
        }
    }

    /**
     * =============================================================================
     * Delete Signature
     * -----------------------------------------------------------------------------
     * Deletes a signature, only if the document belongs to the user's company.
     *
     * @param Signature $signature Signature instance to delete
     * @return void
     * @throws Exception If the signature's document does not belong to user's company or deletion fails
     * =============================================================================
     */
    public function delete(Signature $signature): void
    {
        $user = Auth::user();

        if ($signature->document->company_id !== $user->company_id) {
            throw new Exception('Você não pode deletar assinaturas de outra empresa.');
        }

        try {
            $signature->delete();
        } catch (Exception $e) {
            Log::error("Error deleting signature {$signature->id}: {$e->getMessage()}");
            throw new Exception('Falha ao deletar assinatura.');
        }
    }

    /**
     * =============================================================================
     * Notify User
     * -----------------------------------------------------------------------------
     * Sends notification to the user associated with the signature.
     *
     * @param Signature $signature Signature instance
     * @return void
     * @throws Exception If user is not found or notification fails
     * =============================================================================
     */
    public function notify(Signature $signature): void
    {
        try {
            $user = $signature->user;
            if (!$user) {
                throw new Exception('Usuário não encontrado para esta assinatura.');
            }

            // Placeholder for real notification logic
            // $user->notify(new SignatureNotification($signature));

        } catch (Exception $e) {
            Log::error("Error notifying user of signature {$signature->id}: {$e->getMessage()}");
            throw new Exception('Falha ao notificar usuário.');
        }
    }
}
