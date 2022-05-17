<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('mark');
            $table->integer('number_of_attempts');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tests_id');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('test_id')->references('id')->on('tests');
            $table->foreign('subject_id')->references('id')->on('subject_of_studies');
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
        Schema::dropIfExists('result_tests');
    }
}
