<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "relays".
 *
 * @property integer $id
 * @property string $name
 * @property string $topic
 * @property integer $state
 * @property string $created_at
 * @property string $updated_at
 */
class Relays extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'relays';
}
