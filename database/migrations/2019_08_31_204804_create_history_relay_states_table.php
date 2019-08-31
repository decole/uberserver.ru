<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryRelayStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_relay_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_relay');
            $table->string('topic');
            $table->boolean('state');
            $table->timestamps();

            $table->primary('id');
            $table->index('id_relay');
            $table->index('topic');
            $table->index('created_at');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_relay_states');
    }
}
