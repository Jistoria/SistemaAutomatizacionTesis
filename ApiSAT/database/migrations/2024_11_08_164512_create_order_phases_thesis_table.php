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
        Schema::create('order_phases_thesis', function (Blueprint $table) {
            $table->uuid('order_phases_thesis_id')->primary();
            $table->uuid('previous_phases_id')->nullable();
            $table->uuid('next_phases_id')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->uuid('thesis_phases_id')->nullable();

            $table->foreign('thesis_phases_id')->references('thesis_phases_id')->on('thesis_phases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_phases_thesis');
    }
};
