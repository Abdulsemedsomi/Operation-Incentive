<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailyreports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reports_to')->unsigned()->nullable();

            $table->bigInteger('report_id')->unsigned();
            $table->bigInteger('task_id')->unsigned();
            $table->bigInteger('subtask_id')->unsigned()->nullable();
			$table->double('status')->default(0);
			$table->string('unplanned_task')->nullable();

			$table->string('feedback')->nullable();
            $table->foreign('reports_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('set null');

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
        Schema::dropIfExists('dailyreports');
    }
}
