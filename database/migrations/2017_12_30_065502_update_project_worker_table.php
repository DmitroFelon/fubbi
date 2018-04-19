<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('project_worker');

        Schema::create('project_worker', function (Blueprint $table) {
            $table->integer('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
