<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration {
	public function up()
	{
		// Schema::create('thesis-process-student', function(Blueprint $table) {
		// 	$table->bigIncrements('id');
		// 	$table->timestamps();
		// 	$table->softDeletes();
		// });
	}
	
	public function down()
	{
		// Don't listen to the haters
		// Schema::dropIfExists('thesis-process-student');
	}
};
