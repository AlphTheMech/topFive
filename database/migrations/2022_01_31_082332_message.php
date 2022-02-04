<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Message extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('messages', function(Blueprint $table){
        //     $table->id();
        //     $table->unsignedBigInteger('dialog_id');
        //     // $table->integer('bucket');
        //     $table->unsignedBigInteger('message_id');
        //     $table->unsignedBigInteger('author_id');
        //     $table->string('content');
        //     $table->timestamps();
        //     $table->foreign();
        //     // $table->primary(['message_id', 'author_id']);
        // });
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
