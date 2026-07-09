<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all tables
        $tables = Schema::getTables();
        
        foreach ($tables as $tableInfo) {
            $table = $tableInfo['name'];
            
            // Skip migrations and internal tables
            if (in_array($table, ['migrations', 'sqlite_sequence', 'failed_jobs', 'password_resets', 'password_reset_tokens', 'personal_access_tokens', 'sessions'])) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                $hasCreatedAt = Schema::hasColumn($table, 'created_at');
                $hasUpdatedAt = Schema::hasColumn($table, 'updated_at');

                if (!$hasCreatedAt && !$hasUpdatedAt) {
                    $tableBlueprint->timestamps();
                } elseif (!$hasCreatedAt) {
                    $tableBlueprint->timestamp('created_at')->nullable();
                } elseif (!$hasUpdatedAt) {
                    $tableBlueprint->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not dropping timestamps to prevent data loss on rollback
    }
};
