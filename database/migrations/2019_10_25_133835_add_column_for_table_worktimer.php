<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForTableWorktimer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_timer', function (Blueprint $table) {
            $table->string('topic');
            $table->string('command_on');
            $table->string('command_off');
            $table->string('linked')->nullable(); // home, watering
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_timer', function (Blueprint $table) {
            $table->dropColumn(['topic', 'command_on', 'command_off', 'linked']);
        });
    }
}
