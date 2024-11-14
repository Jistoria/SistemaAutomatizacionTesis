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
        Schema::create('observations_phases', function (Blueprint $table) {
            $table->uuid('observation_phase_id')->primary();
            $table->text('comment')->nullable();
            $table->uuid('created_by_user');
            $table->uuid('thesis_process_phases_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('thesis_process_phases_id')->references('thesis_process_phases_id')->on('thesis_process_phases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations_phases_students');
    }
};
