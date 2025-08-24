<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Signature;
use App\Models\Company;

/**
 * ============================================================================
 * DocumentSignatureSeeder
 * ============================================================================
 * Seeds the database with documents and their corresponding signatures.
 *
 * Responsibilities:
 *   • Creates 5 documents for each status ('pending', 'signed', 'rejected') per company
 *   • Assigns 1 to 3 random signatures to each document
 *   • Ensures signatures are associated with users from the same company
 *
 * Usage Example:
 *   php artisan db:seed --class=DocumentSignatureSeeder
 *
 * @package Database\Seeders
 * ============================================================================
 */
class DocumentSignatureSeeder extends Seeder
{
    /**
     * =========================================================================
     * Run Seeder
     * -------------------------------------------------------------------------
     * Seeds documents with multiple statuses and assigns signatures.
     *
     * Workflow:
     *   1. Iterate through all existing companies
     *   2. For each status ('pending', 'signed', 'rejected'):
     *       a. Create 5 documents
     *       b. For each document, randomly select 1 to 3 users from the same company
     *       c. Create signatures for the selected users
     *
     * @return void
     * =========================================================================
     */
    public function run(): void
    {
        $statuses = ['pending', 'signed', 'rejected'];

        // Iterate through all companies
        Company::all()->each(function (Company $company) use ($statuses) {
            $users = $company->users;

            foreach ($statuses as $status) {
                for ($i = 0; $i < 5; $i++) { // 5 documents per status
                    // Create document for company
                    $document = Document::factory()->create([
                        'company_id' => $company->id,
                        'user_id' => $users->random()->id, // Random creator
                        'status' => $status,
                    ]);

                    // Determine number of signatures (1 to 3, or max available users)
                    $signaturesCount = rand(1, min(3, $users->count()));

                    // Select random users for signatures
                    $selectedUsers = $users->random($signaturesCount);

                    // Create signatures
                    foreach ($selectedUsers as $user) {
                        Signature::factory()->create([
                            'document_id' => $document->id,
                            'user_id' => $user->id,
                        ]);
                    }
                }
            }
        });
    }
}
