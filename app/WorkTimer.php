<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "work_timer".
 *
 * @property integer        $id
 * @property string         $name
 * @property integer        $periodic
 * @property boolean        $active
 * @property \Carbon\Carbon $time_start
 * @property \Carbon\Carbon $time_end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string         $topic
 * @property string         $command_on
 * @property string         $command_off
 * @property string         $linked
 */

class WorkTimer extends Model
{
    protected $table = 'work_timer';

}
