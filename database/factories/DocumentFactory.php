<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Document;
use App\Models\Company;
use App\Models\User;

/**
 * ============================================================================
 * DocumentFactory
 * ============================================================================
 * Factory for generating fake Document instances for testing or seeding.
 *
 * Responsibilities:
 *   • Generate documents with title, file path, company, user, and status
 *   • Allow explicit assignment of company, user, and status
 *   • Integrates with seeder logic for consistent test data
 *
 * Usage Example:
 *   Document::factory()->forCompany($companyId)->forUser($userId)->withStatus('pending')->create();
 *
 * @package Database\Factories
 * ============================================================================
 */
class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * =========================================================================
     * Define default state.
     * -------------------------------------------------------------------------
     * Provides default fake data for a document instance.
     *
     * @return array
     * =========================================================================
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'file_path' => $this->faker->filePath(),
            'company_id' => Company::factory(), // fallback if not provided
            'user_id' => User::factory(),       // fallback if not provided
            'status' => $this->faker->randomElement(['pending', 'signed', 'rejected']),
        ];
    }

    /**
     * =========================================================================
     * Assign a specific company to the document.
     * -------------------------------------------------------------------------
     * Sets the company_id field to ensure the document belongs to a given company.
     *
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     * =========================================================================
     */
    public function forCompany(int $companyId)
    {
        return $this->state(function () use ($companyId) {
            return [
                'company_id' => $companyId,
            ];
        });
    }

    /**
     * =========================================================================
     * Assign a specific user as the creator of the document.
     * -------------------------------------------------------------------------
     * Sets the user_id field to ensure the document has a designated creator.
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

    /**
     * =========================================================================
     * Set a specific status for the document.
     * -------------------------------------------------------------------------
     * Allows overriding the randomly generated status with 'pending', 'signed', or 'rejected'.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     * =========================================================================
     */
    public function withStatus(string $status)
    {
        return $this->state(function () use ($status) {
            return [
                'status' => $status,
            ];
        });
    }
}
