<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChannelTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_template', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('company_id');

            $table->string('name');

            $table->text('content');

            $table->string('logo');
            $table->string('name_mod');
            $table->string('desc_mod');
            $table->string('name_mod_cp');
            $table->integer('video1_id');

            $table->integer('video2_id');

            $table->integer('video3_id');



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
