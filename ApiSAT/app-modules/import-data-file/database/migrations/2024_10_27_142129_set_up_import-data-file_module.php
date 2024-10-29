<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up()
	{
		Schema::create('thesis_titles', function(Blueprint $table) {
			$table->uuid('thesis_id')->primary();
            $table->text('title');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::create('degrees', function(Blueprint $table) {
            $table->uuid('degree_id')->primary();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('period_academic', function(Blueprint $table) {
			$table->uuid('period_academic_id')->primary();
            $table->string('name')->unique();
            $table->date('start_date');
            $table->date('end_date');
			$table->timestamps();
            $table->uuid('created_by_user');
            $table->uuid('updated_by_user');
            $table->uuid('deleted_by_user')->nullable();
			$table->softDeletes();
		});

		Schema::create('students', function(Blueprint $table) {
			$table->uuid('student_id')->references('id')->on('users');
            $table->uuid('thesis_id');
            $table->uuid('degree_id');
            $table->string('dni');
			$table->timestamps();
			$table->softDeletes();
            $table->date('enrollment_date');
            $table->uuid('created_by_user');
            $table->uuid('updated_by_user');
            $table->uuid('deleted_by_user')->nullable();
		});

        Schema::create('teachers', function(Blueprint $table) {
            $table->uuid('teacher_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by_user');
            $table->uuid('updated_by_user');
            $table->uuid('deleted_by_user')->nullable();
        });
	}

	public function down()
	{
		// Don't listen to the haters
		// Schema::dropIfExists('import-data-file');
	}
};
