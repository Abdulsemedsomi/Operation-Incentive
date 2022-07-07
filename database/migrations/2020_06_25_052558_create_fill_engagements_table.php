<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFillEngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fill_engagements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issuer');
            $table->foreign('issuer')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('issued_to');
            $table->foreign('issued_to')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('report_id');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->unsignedBigInteger('engagement_id');
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
            $table->string('Description');
            $table->string('Reason');
            $table->string('Improvement')->nullable();
            $table->string('CC');
            $table->string('Action')->nullable();
            $table->double('Score')->default(0);
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
        Schema::dropIfExists('fill_engagements');
    }
}
