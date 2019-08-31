<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "weather".
 *
 * @property integer $id
 * @property string $temperature
 * @property string $spec
 * @property integer $date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Weather extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'weather';
}
