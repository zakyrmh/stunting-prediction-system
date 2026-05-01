<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add posyandu_id foreign key constraint to users table.
     *
     * Note: The 'role' enum column and 'posyandu_id' column were already added
     * in the initial create_users migration (0001_01_01_000000_create_users_table.php).
     * This migration only adds the foreign key constraint for relational integrity.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('posyandu_id')
                ->references('id')
                ->on('posyandus')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['posyandu_id']);
        });
    }
};
