<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sqlite_history';

    public function up(): void
    {
        Schema::connection('sqlite_history')->table('access_logs', function (Blueprint $table) {
            $table->text('error_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('sqlite_history')->table('access_logs', function (Blueprint $table) {
            $table->dropColumn('error_text');
        });
    }
};
