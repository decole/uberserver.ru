<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relays;

/**
 * This is the model class for table "history_relay_states".
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
    public static function historySave($id, $value)
    {
        $model = new self();
        $model->id_relay = $id;
        $model->topic = Relays::where('id', $id)->first()->topic;
        $model->state = $value;
        $model->save();

    }

}
