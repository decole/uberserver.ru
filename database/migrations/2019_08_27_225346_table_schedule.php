<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('command');
            $table->string('interval');
            $table->dateTime('last_run')->nullable();
            $table->dateTime('next_run')->nullable();
            $table->timestamp('created')->useCurrent();
            $table->timestamp('updated')->useCurrent();

            $table->index('next_run');
            $table->index('command');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule');
    }
}
