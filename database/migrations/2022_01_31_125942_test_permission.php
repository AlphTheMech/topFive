<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests_permissions', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tests_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tests_id')->references('id')->on('tests');
            $table->primary(['user_id', 'tests_id']);
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
