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
            try {
                DB::statement('ALTER TABLE equipment DROP INDEX inv_number_UNIQUE');
            } catch (Throwable $e) {
                // Ignore if index doesn't exist
            }

            DB::statement('ALTER TABLE equipment MODIFY COLUMN inv_number VARCHAR(100) NULL DEFAULT NULL');

            try {
                DB::statement('ALTER TABLE equipment ADD INDEX equipment_inv_number_index (inv_number)');
            } catch (Throwable $e) {
                // Ignore if index already exists
            }
        } else {
            Schema::table('equipment', function (Blueprint $table) {
                $table->string('inv_number', 100)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE equipment DROP INDEX equipment_inv_number_index');
            } catch (Throwable $e) {
                // Ignore
            }

            DB::statement('ALTER TABLE equipment MODIFY COLUMN inv_number INT NULL DEFAULT NULL');

            try {
                DB::statement('ALTER TABLE equipment ADD UNIQUE INDEX inv_number_UNIQUE (inv_number)');
            } catch (Throwable $e) {
                // Ignore
            }
        }
    }
};
