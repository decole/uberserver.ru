<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "alice_secure".
 *
 * @property int $id
 * @property int $id_relay
 * @property string $topic
 * @property boolean $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class HistoryRelayState extends Model
{
    //$table->bigIncrements('id');
    //            $table->integer('id_relay');
    //            $table->string('topic');
    //            $table->boolean('state');
}
