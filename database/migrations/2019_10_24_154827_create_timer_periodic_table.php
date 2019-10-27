<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimerPeriodicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_timer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('periodic', false, true);
            $table->dateTime('time_start');
            $table->dateTime('time_end');
            $table->boolean('active');
            $table->timestamps();

            $table->index('id','id_timer');
            $table->index('time_start','time_start');
            $table->index('active','active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_timer');
    }
}
