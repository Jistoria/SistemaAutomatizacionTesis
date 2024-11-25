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
        Schema::create('pre_requirements', function (Blueprint $table) {
            $table->uuid('pre_requirements_id')->primary();
            $table->uuid('thesis_phases_id')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('extension')->nullable();
            $table->string('url_resource')->nullable();
            $table->text('description')->nullable();
            $table->string('approval_role')->nullable();
            $table->timestamps();
            $table->uuid('created_by_user')->nullable();
            $table->uuid('updated_by_user')->nullable();
            $table->uuid('deleted_by_user')->nullable();
            $table->softDeletes();

            $table->foreign('thesis_phases_id')->references('thesis_phases_id')->on('thesis_phases')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prerequirements');
    }
};
