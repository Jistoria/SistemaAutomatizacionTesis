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
        Schema::create('phase_requests', function (Blueprint $table) {
            $table->uuid('request_id')->primary(); // Primary Key (UUID)
            $table->uuid('thesis_process_id'); // UUID Foreign Key
            $table->uuid('student_id'); // UUID Foreign Key
            $table->uuid('requested_phase_id'); // UUID Foreign Key
            $table->timestamp('request_date')->nullable();
            $table->enum('state', ['Enviado', 'Aprobado', 'Rechazado'])->default('Enviado');
            $table->timestamp('review_date')->nullable();
            $table->timestamp('approved_date')->nullable(); // Fecha de aprobación
            $table->uuid('reviewed_by')->nullable(); // UUID Foreign Key (revisor)
            $table->text('comments')->nullable();
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // deleted_at

            // Relación con otras tablas
            $table->foreign('thesis_process_id')->references('thesis_process_id')->on('thesis_process')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('requested_phase_id')->references('thesis_phases_id')->on('thesis_phases')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null'); // Relación con usuarios
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phase_requets');
    }
};
