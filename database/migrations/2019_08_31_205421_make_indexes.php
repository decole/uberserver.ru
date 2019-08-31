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
            $table->index('session_id','session_id');
        });

        Schema::table('alice_secure', function (Blueprint $table) {
            $table->index('user_id','user_id');
        });

        Schema::table('mqtt_payload', function (Blueprint $table) {
            $table->index('topic','topic');
        });

        Schema::table('weather', function (Blueprint $table) {
            $table->index('date','date');
            $table->index('created_at','created_at');
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
