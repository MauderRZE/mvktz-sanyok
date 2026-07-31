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
            DB::statement("ALTER TABLE equipment MODIFY COLUMN status VARCHAR(50) DEFAULT 'в експлуатації'");
            DB::statement('UPDATE equipment SET status = LOWER(status)');
        } else {
            Schema::table('equipment', function (Blueprint $table) {
                $table->string('status', 50)->default('в експлуатації')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE equipment MODIFY COLUMN status VARCHAR(50) DEFAULT 'В експлуатації'");
        }
    }
};
