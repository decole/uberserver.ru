<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "telegram".
 *
 * @property int     $id
 * @property string  $mesage
 * @property integer $update_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Telegram extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'telegram';
}
