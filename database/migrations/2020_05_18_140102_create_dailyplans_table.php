<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailyplans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('planid')->unsigned();
			$table->bigInteger('task_id')->unsigned();
			$table->bigInteger('subtask_id')->unsigned()->nullable();
            $table->unsignedBigInteger('report_to')->nullable();

            $table->foreign('report_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('planid')->references('id')->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('dailyplans');
    }
}
