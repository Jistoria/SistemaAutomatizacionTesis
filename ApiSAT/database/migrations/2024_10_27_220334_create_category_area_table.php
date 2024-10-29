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
        Schema::create('category_area', function (Blueprint $table) {
            $table->uuid('category_area_id')->primary();
            $table->string('name');
            $table->string('description');
            $table->uuid('created_by_user');
            $table->uuid('updated_by_user');
            $table->uuid('deleted_by_user');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_area');
    }
};
