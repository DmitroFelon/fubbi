<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeamUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('team_user');

        Schema::create('team_user', function (Blueprint $table) {
            $table->integer('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['team_id', 'user_id']);
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
