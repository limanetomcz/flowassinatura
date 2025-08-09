<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as alterações na tabela users.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna boolean is_admin após a coluna password
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    /**
     * Reverte as alterações feitas no método up.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove a coluna is_admin
            $table->dropColumn('is_admin');
        });
    }
};
