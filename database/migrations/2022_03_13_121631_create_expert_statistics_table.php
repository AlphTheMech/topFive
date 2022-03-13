<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->unsignedBigInteger('test_id');
            $table->integer('statistics_score')->nullable();
            // $table->primary('expert_id');
            $table->foreign('expert_id')->references('id')->on('users');
            $table->foreign('test_id')->references('id')->on('tests');
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
        Schema::dropIfExists('expert_statistics');
    }
}
