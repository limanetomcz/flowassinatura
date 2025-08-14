<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
        });
        Schema::dropIfExists('empresas');
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('document');
            $table->string('contact_email')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('documento');
            $table->string('email_contato')->nullable();
            $table->string('telefone')->nullable();
            $table->timestamps();
        });
    }
};
