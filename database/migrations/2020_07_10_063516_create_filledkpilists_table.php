<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilledkpilistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filledkpilists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filledkpi_id');
            $table->foreign('filledkpi_id')->references('id')->on('filledkpis')->onDelete('cascade');
            $table->unsignedBigInteger('kpiform_id');
            $table->foreign('kpiform_id')->references('id')->on('kpiforms')->onDelete('cascade');
            $table->integer('actual');
            $table->double('score');
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
        Schema::dropIfExists('filledkpilists');
    }
}
