<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weeklyplans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planid');
            $table->unsignedBigInteger('report_to')->nullable();
			$table->unsignedBigInteger('keyresult_id')->unsigned();
			$table->unsignedBigInteger('task_id')->unsigned();
			$table->double('keyresult_percent')->default(0);
			$table->integer('keyresult_priority')->default(0);
			$table->integer('task_priority')->default(0);
            $table->double('task_percent')->default(0);

            $table->foreign('report_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('planid')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('keyresult_id')->references('id')->on('keyresults')->onDelete('cascade');;
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');;

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
        Schema::dropIfExists('weeklyplans');
    }
}
