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
        Schema::create('thesis_modules', function (Blueprint $table) {
            $table->uuid('thesis_module_id')->primary();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->uuid('created_by_user')->nullable();
            $table->uuid('updated_by_user')->nullable();
            $table->uuid('deleted_by_user')->nullable();
            $table->softDeletes();
        });

        Schema::table('thesis_phases', function (Blueprint $table) {
            $table->uuid('thesis_module_id')->nullable();

            $table->foreign('thesis_module_id')->references('thesis_module_id')->on('thesis_modules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
