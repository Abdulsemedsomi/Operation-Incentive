<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyresults', function (Blueprint $table) {
            $table->id();
            $table->string('keyresult_name');
            $table->integer('keyresult_type')->default(0); //Achieved or not / Should increase to
            $table->integer('AchievedStatus')->default(0); // null if type is should increase to
            $table->double('targetValue')->nullable(); // null if type is achieved or not
            $table->double('initialValue')->nullable(); // null if type is achieved or not
            $table->double('currentState')->nullable();

            $table->unsignedBigInteger('objective_id')->nullable();
            $table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
            $table->integer('active')->default(0);
            $table->double('attainment')->default(0);
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
        Schema::dropIfExists('keyresults');
    }
}
