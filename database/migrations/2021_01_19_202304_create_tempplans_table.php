<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempplans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dailyplanid')->unsigned();
            $table->bigInteger('task_id')->unsigned()->nullable();
			$table->bigInteger('subtask_id')->unsigned()->nullable();
			$table->foreign('dailyplanid')->references('id')->on('dailyplans')->onDelete('cascade');
            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('set null');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('tempplans');
    }
}
