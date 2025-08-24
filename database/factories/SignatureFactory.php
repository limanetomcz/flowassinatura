<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Signature;

/**
 * ============================================================================
 * SignatureFactory
 * ============================================================================
 * Factory for generating fake Signature instances for testing or seeding.
 *
 * Responsibilities:
 *   • Generate signatures linked to documents and users
 *   • Simulate digital signature hash and signed timestamp
 *   • Supports explicit assignment of document and user
 *
 * Usage Example:
 *   Signature::factory()->forDocument($documentId)->forUser($userId)->create();
 *
 * @package Database\Factories
 * ============================================================================
 */
class SignatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Signature::class;

    /**
     * =========================================================================
     * Define default state.
     * -------------------------------------------------------------------------
     * Provides default fake data for a signature instance. Requires explicit
     * document_id and user_id to ensure relationships are valid.
     *
     * @return array
     * =========================================================================
     */
    public function definition()
    {
        return [
            'document_id' => null,       // must be provided explicitly
            'user_id' => null,           // must be provided explicitly
            'signature_hash' => $this->faker->sha256,
            'signed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * =========================================================================
     * Assign a specific document to this signature.
     * -------------------------------------------------------------------------
     * Sets the document_id field to link the signature to a given document.
     *
     * @param int $documentId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     * =========================================================================
     */
    public function forDocument(int $documentId)
    {
        return $this->state(function () use ($documentId) {
            return [
                'document_id' => $documentId,
            ];
        });
    }

    /**
     * =========================================================================
     * Assign a specific user to this signature.
     * -------------------------------------------------------------------------
     * Sets the user_id field to link the signature to a given user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     * =========================================================================
     */
    public function forUser(int $userId)
    {
        return $this->state(function () use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}
