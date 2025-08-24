<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration creates the "signatures" table, which stores digital signatures
     * associated with documents. Each signature belongs to a document and a user,
     * and stores the hash of the digital signature along with the timestamp when signed.
     */
    public function up(): void
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign key: document that is being signed
            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->onDelete('cascade')
                  ->comment('Document associated with this signature');

            // Foreign key: user who signed the document
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User who created this signature');

            // Digital hash representing the signature
            $table->string('signature_hash', 512)
                  ->comment('Hash representing the digital signature');

            // Timestamp when the document was signed
            $table->timestamp('signed_at')->nullable()->comment('Date and time when the signature was applied');

            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Index for faster lookups by document and user
            $table->index(['document_id', 'user_id'], 'signatures_document_user_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the "signatures" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
