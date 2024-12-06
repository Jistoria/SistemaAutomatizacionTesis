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
        Schema::create('observations_requirements', function (Blueprint $table) {
            $table->uuid('observation_requirement_id')->primary();
            $table->text('comment');
            $table->uuid('created_by_user');
            $table->uuid('student_requirements_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_requirements_id')->references('student_requirements_id')->on('student_requirements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations_requirements');
    }
};
