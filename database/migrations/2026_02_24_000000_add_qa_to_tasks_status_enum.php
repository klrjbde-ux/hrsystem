<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip this migration for SQLite
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // MySQL only
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('to_do', 'in_progress', 'qa', 'completed') DEFAULT 'to_do'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('to_do', 'in_progress', 'completed') DEFAULT 'to_do'");
    }
};