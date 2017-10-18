<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Count extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('count',function (Blueprint $table){

            $table->increments('id');

            $table->string('openid');

            $table->integer('channel_id');

            $table->string('name');

            $table->string('nickname');

            $table->string('ip');

            $table->string('system');

            $table->string('avatar');

            $table->string('type');//1 直播 2 点播

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
