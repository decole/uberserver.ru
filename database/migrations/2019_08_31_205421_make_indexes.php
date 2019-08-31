<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alice', function (Blueprint $table) {
            $table->primary('id');
            $table->index('session_id');
        });

        Schema::table('alice_secure', function (Blueprint $table) {
            $table->primary('id');
            $table->index('user_id');
        });

        Schema::table('mqtt_payload', function (Blueprint $table) {
            $table->primary('id');
            $table->index('topic');
        });

        Schema::table('weather', function (Blueprint $table) {
            $table->primary('id');
            $table->index('date');
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
        //
    }
}
