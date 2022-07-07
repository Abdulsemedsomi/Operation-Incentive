<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keyresultid')->unsigned();

			$table->string('taskname');
            $table->integer('isMilestone')->default(0);
            $table->integer('status')->default(0);
            $table->integer('isactive')->default(1);
            $table->bigInteger('parent_task')->unsigned()->nullable();
            $table->foreign('parent_task')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('keyresultid')->references('id')->on('keyresults')->onDelete('cascade');
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
        Schema::dropIfExists('tasks');
    }
}
