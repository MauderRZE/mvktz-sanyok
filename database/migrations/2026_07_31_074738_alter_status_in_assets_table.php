<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assets MODIFY COLUMN status VARCHAR(50) DEFAULT 'працює'");
            DB::statement('UPDATE assets SET status = LOWER(status)');
        } else {
            Schema::table('assets', function (Blueprint $table) {
                $table->string('status', 50)->default('працює')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('Працює','Потребує уваги','В ремонті','На списання','Списано') DEFAULT 'Працює'");
        }
    }
};
