<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration creates the "documents" table, which stores all documents
     * that can be signed digitally by users. Each document is linked to a company
     * and a creator (user), and has a status indicating its signing state.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // Primary key

            // Document title
            $table->string('title', 255)->comment('Title or name of the document');

            // File path or storage reference
            $table->string('file_path', 1024)->comment('Path to the stored document file');

            // Foreign key: company that owns the document
            $table->foreignId('company_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('Company associated with this document');

            // Foreign key: user who created the document
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('User who created this document');

            // Status of the document (pending, signed, rejected)
            $table->enum('status', ['pending', 'signed', 'rejected'])
                  ->default('pending')
                  ->comment('Current status of the document');

            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['company_id', 'user_id'], 'documents_company_user_idx');
            $table->index('status', 'documents_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the "documents" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
