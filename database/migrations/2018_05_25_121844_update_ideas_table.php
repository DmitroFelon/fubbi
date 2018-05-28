<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ideas');
        Schema::create('ideas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index();
            $table->string('type')->index();
            $table->string('theme');
            $table->text('article_format_type')->nullable();
            $table->text('link_to_model_article')->nullable();
            $table->text('references')->nullable();
            $table->text('points_covered')->nullable();
            $table->text('points_avoid')->nullable();
            $table->text('additional_notes')->nullable();
            $table->boolean('completed')->default(false)->nullable();
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
