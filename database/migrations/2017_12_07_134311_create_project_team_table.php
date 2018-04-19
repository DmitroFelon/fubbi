<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('project_team');
        
        Schema::create(
            'project_team',
            function (Blueprint $table) {
                $table->integer('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->integer('team_id')->references('id')->on('teams')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_team');
    }
}
