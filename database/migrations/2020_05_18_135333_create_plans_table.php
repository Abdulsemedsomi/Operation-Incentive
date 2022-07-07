<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plantype');
            $table->bigInteger('sessionid')->unsigned();
            $table->foreign('sessionid')->references('id')->on('sessions')->onDelete('cascade');

            $table->bigInteger('userid')->unsigned();
            $table->bigInteger('teamid')->unsigned();
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teamid')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('isReported')->default(0);
            $table->integer('isEdited')->default(0);

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
        Schema::dropIfExists('plans');
    }
}
