<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Channel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel',function (Blueprint $table){

            $table->increments('id');

            $table->string('name');

            $table->integer('channel_id');

            $table->integer('video1_id');

            $table->integer('video2_id');

            $table->integer('video3_id');

            $table->string('content')->nullable();

            $table->string('name_mod')->nullable();

            $table->string('name_mod_cp')->nullable();

            $table->text('desc_mod')->nullable();

            $table->string('logo')->nullable();

            $table->boolean('open')->default(1);

            $table->integer('channel_videos_id')->default(0);

            $table->integer('session_id')->default(0);

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
