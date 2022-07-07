<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_incentives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session');
            $table->string('project_name')->nullable();
            $table->string('project_amount')->nullable();
            $table->string('task_name')->nullable();
            $table->string('task_amount')->nullable();
            $table->string('milestone_name')->nullable();
            $table->string('milestone_amount')->nullable();
            $table->string('user')->nullable();
            $table->string('position')->nullable();
            $table->string('%comp')->nullable();
            $table->string('SAC')->nullable();
            $table->string('actual_time')->nullable();
            $table->string('earned_schedule')->nullable();
            $table->string('SPI')->nullable();
            
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
        Schema::dropIfExists('operation_incentives');
    }
}
