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

        Schema::create('thesis_phases', function (Blueprint $table) {
            $table->uuid('thesis_phases_id')->primary();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->uuid('created_by_user')->nullable();
            $table->uuid('updated_by_user')->nullable();
            $table->uuid('deleted_by_user')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_phases');
    }
};
