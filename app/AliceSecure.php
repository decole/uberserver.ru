<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "alice_secure".
 *
 * @property int $id
 * @property string $user_id
 * @property int $valid
 */
class AliceSecure extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'alice_secure';

    public $timestamps = false; // отключаем created_at и updated_at http://laravel.su/docs/5.4/eloquent

    const BLOCKED = 'Заблокирован';
    const VALID   = 'Зарегистрирован';
    const ADMIN   = 'Админ';

    public static function getValidStatus()
    {
        return [
            static::BLOCKED  => '0',
            static::VALID    => '1',
            static::ADMIN    => '2',
        ];

    }

    /**
     * Добавление пользователся в доверенную зону
     *
     * @param $id
     * @return void
     */
    public function registerUser($id)
    {
//        $model = self::where(['user_id' => $id])->first();
//        var_dump($model);

        if(self::where(['user_id' => $id])->first() === null) {
            $model = new self();
            $model->user_id = $id;
            $model->valid = 1;
            $model->save();
        }

    }

    /**
     * @param $id
     * @return bool
     */
    public static function validateUser($id)
    {
        $validate = self::where(['user_id' => $id])->first();
        return !($validate === null);

    }

    /**
     * @param $id
     * @return bool
     */
    public static function isAdmin($id)
    {
        $admin = self::getValidStatus();
        $validate = self::where(['user_id' => $id, 'valid' => $admin[self::ADMIN]])->first();
        return !($validate === null);

    }

//    /**
//     * @return bool
//     */
//    public function blocking()
//    {
//        // foreach blocking all users
//        return true;
//
//    }

//    /**
//     * @return bool
//     */
//    public function backup()
//    {
//        // foreach backup all
//        return true;
//
//    }

//    /**
//     * Destroy system
//     */
//    public function destroy()
//    {
//        shell_exec('/home/decole/fuckServer.py');
//
//    }

}
