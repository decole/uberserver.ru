<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "site_notifications".
 *
 * @property int     $id
 * @property int     $user
 * @property string  $message
 * @property boolean $isRead
 * @property string  $notificator
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class SiteNotifications extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'site_notifications';
}
