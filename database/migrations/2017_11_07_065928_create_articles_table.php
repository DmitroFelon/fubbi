<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		

		Schema::create('articles', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('project_id')->index();
			$table->boolean('accepted')->default(false);
			$table->tinyInteger('attempts')->default(0);
			$table->string('title');
			$table->string('body');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('articles');
	}
}
