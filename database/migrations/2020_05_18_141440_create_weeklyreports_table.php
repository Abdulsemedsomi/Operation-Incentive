<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weeklyreports', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('reports_to')->unsigned()->nullable();
            $table->bigInteger('keyresult_id')->unsigned();
            $table->bigInteger('report_id')->unsigned();
            $table->bigInteger('task_id')->unsigned()->nullable();
			$table->double('Keyresult_target')->default(0);

            $table->double('task_status')->default(0);
            $table->double('task_target')->default(0);
			$table->string('unplanned_task')->nullable();

            $table->string('feedback')->nullable();
            $table->foreign('keyresult_id')->references('id')->on('keyresults')->onDelete('cascade');

            $table->foreign('reports_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('set null');


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
        Schema::dropIfExists('weeklyreports');
    }
}
