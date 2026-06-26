<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite_errors')->create('system_errors', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->nullable();
            $table->string('error_type')->nullable();
            $table->text('error_text')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite_errors')->dropIfExists('system_errors');
    }
}
