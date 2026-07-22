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
        if (! Schema::hasColumn('movements', 'location_id')) {
            Schema::table('movements', function (Blueprint $table) {
                $table->integer('location_id')->nullable()->after('asset_id');
                $table->index('location_id');
            });
        } else {
            Schema::table('movements', function (Blueprint $table) {
                $table->integer('location_id')->nullable()->change();
                $table->index('location_id');
            });
        }

        // Backfill location_id for existing records using current asset location
        DB::statement('
            UPDATE movements m 
            JOIN assets a ON m.asset_id = a.id 
            SET m.location_id = a.current_loc_id 
            WHERE m.location_id IS NULL AND a.current_loc_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('movements', 'location_id')) {
            Schema::table('movements', function (Blueprint $table) {
                $table->dropIndex(['location_id']);
                $table->dropColumn('location_id');
            });
        }
    }
};
