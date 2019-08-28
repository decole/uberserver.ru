<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id', 40);
            $table->string('user_id', 80);
            $table->string('command', 255);
            $table->string('tokens', 255);
            $table->text('json');
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
        Schema::dropIfExists('alice');
    }
}
