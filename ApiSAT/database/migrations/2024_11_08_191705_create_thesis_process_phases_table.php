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
        Schema::create('thesis_process_phases', function (Blueprint $table) {
            $table->uuid('thesis_process_phases_id')->primary();
            $table->uuid('thesis_process_id'); // Foreign key, assuming it refers to another table
            $table->uuid('teacher_id'); // Foreign key, assuming it refers to another table
            $table->uuid('student_id'); // Foreign key, assuming it refers to another table
            $table->uuid('thesis_id')->nullable(); // Foreign key, assuming it refers to another table
            $table->uuid('period_academic_id')->nullable(); // Foreign key, assuming it refers to another table
            $table->uuid('thesis_phases_id'); // Foreign key referencing thesis_phases

            $table->boolean('approval')->default(false);
            $table->string('state_now')->nullable();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->text('observations')->nullable();

            // Foreign key constraints
            $table->foreign('thesis_process_id')->references('thesis_process_id')->on('thesis_process')->onDelete('cascade');
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('set null');
            $table->foreign('thesis_id')->references('thesis_id')->on('thesis_titles')->onDelete('set null');
            $table->foreign('period_academic_id')->references('period_academic_id')->on('period_academic');
            $table->foreign('thesis_phases_id')->references('thesis_phases_id')->on('thesis_phases');

            $table->timestamps();
            $table->softDeletes();
            // Clave única compuesta para los campos especificados
            // $table->unique(['student_id', 'thesis_id', 'period_academic_id', ], 'unique_student_thesis_period_phase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_process_phases');
    }
};
