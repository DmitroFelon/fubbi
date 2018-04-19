<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index();
            $table->string('type')->index();
            $table->string('theme');
            $table->string('points_covered', 1000)->nullable();
            $table->string('points_avoid', 1000)->nullable();
            $table->string('references', 1000)->nullable();
            $table->boolean('this_month')->default(false)->nullable();
            $table->boolean('next_month')->default(false)->nullable();
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
        Schema::dropIfExists('ideas');
    }
}
