<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user'); // id пользователя
            $table->text('message'); // короткое сообщение
            $table->boolean('isRead'); // показывать ли на сайте
            $table->text('notificator'); // тот кто инициировал объявление
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
        Schema::dropIfExists('site_notifications');
    }
}
