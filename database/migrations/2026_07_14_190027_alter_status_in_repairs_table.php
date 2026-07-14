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
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE repairs MODIFY COLUMN status ENUM('В ремонті', 'Відремонтовано', 'Неможливо відремонтувати') DEFAULT 'В ремонті'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE repairs MODIFY COLUMN status ENUM('В ремонті', 'Повернено') DEFAULT 'В ремонті'");
        }
    }
};
