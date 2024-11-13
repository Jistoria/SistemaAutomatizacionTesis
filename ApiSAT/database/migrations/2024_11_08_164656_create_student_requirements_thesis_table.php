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
        Schema::create('student_requirements', function (Blueprint $table) {
            $table->uuid('student_requirements_id')->primary();
            $table->uuid('student_id')->nullable();
            $table->uuid('period_academic_id')->nullable();
            $table->uuid('thesis_phases_id')->nullable();
            $table->uuid('thesis_process_phases_id')->nullable();
            $table->uuid('requirements_id')->nullable();
            $table->text('requirements_data')->nullable();
            $table->boolean('approved')->nullable();
            $table->enum('status', ['Aprovado', 'Pendiente', 'Rechazado', 'Enviado'])->default('Pendiente');
            $table->uuid('approved_by_user')->nullable();
            $table->string('url_file')->nullable();
            $table->timestamp('send_date')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->string('approved_role')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('thesis_phases_id')->references('thesis_phases_id')->on('thesis_phases')->onDelete('set null');
            // Agrega más claves foráneas si otras tablas existen en el esquema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_requirements_thesis');
    }
};
