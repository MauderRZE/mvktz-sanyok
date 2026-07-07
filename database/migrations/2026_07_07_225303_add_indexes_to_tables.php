<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->index('serial_number');
            $table->index('ip_address');
            $table->index('mac_address');
            $table->index('hostname');
            $table->index('status');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->index('account_name');
            $table->index('status');
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->index('last_name');
            $table->index('first_name');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->index('room_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['serial_number']);
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['mac_address']);
            $table->dropIndex(['hostname']);
            $table->dropIndex(['status']);
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex(['account_name']);
            $table->dropIndex(['status']);
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->dropIndex(['last_name']);
            $table->dropIndex(['first_name']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex(['room_number']);
        });
    }
};
