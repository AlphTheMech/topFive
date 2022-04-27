<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubjectTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('subject_tests', function(Blueprint $table){
           $table->unsignedBigInteger('tests_id');
           $table->unsignedBigInteger('subject_of_studies_id');
           $table->foreign('tests_id')->references('id')->on('tests');
           $table->foreign('subject_id')->references('id')->on('subject_of_studies');
           $table->primary(['tests_id','subject_id']);
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
        //
    }
}
