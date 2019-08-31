<?php

namespace App;

use App\Helpers\MqttHelper;
use App\Helpers\WateringHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "alice".
 *
 * @property int $id
 * @property string $session_id
 * @property string $user_id
 * @property string $command
 * @property string $tokens
 * @property string $json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Alice extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'alice';

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session ID',
            'user_id' => 'User ID',
            'command' => 'Command',
            'tokens' => 'Tokens',
            'json' => 'Json',
            'create_date' => 'Create Date',
        ];
    }

    public function saveDialog($apiRequestArray)
    {
        $assistant = new self();
        $assistant->tokens =  var_export($apiRequestArray['request']['nlu']['tokens'], true);
        $assistant->session_id = var_export($apiRequestArray['session']['session_id'], true);
        $assistant->user_id = var_export($apiRequestArray['session']['user_id'], true);
        $assistant->command = var_export($apiRequestArray['request']['command'], true);
        $assistant->json = var_export($apiRequestArray, true);
        $assistant->save();

    }

    /**
     * @var MqttHelper $mqtt
     * @return string
     */
    public function stateAll()
    {
        $mqtt = new MqttHelper();
        $arraySensorState = $mqtt->checkOnline();
        $stateRus = [
            'online'  => 'в сети',
            'offline' => 'не в сети'
        ];
        $online  = 0;
        $offline = 0;
        $request = '';
        foreach ($arraySensorState as $module=>$state) {
            if($state === 'online') {
                $online++;
            }
            if($state === 'offline') {
                $offline++;
            }

            $request .= 'Модуль ' . $module . ' - '.$stateRus[$state].PHP_EOL;
        }

        $stateAll = 'Серый';
        if($online > 0 && $offline === 0) {
            $stateAll = 'Зеленый';
        }
        if($online > 0 && $offline > 0) {
            $stateAll = 'Желтый';
        }
        if($online === 0 && $offline === 0) {
            $stateAll = 'Неизвестен.';
        }
        if($online === 0 && $offline > 0) {
            $stateAll = 'Красный';
        }

        return 'Общий статус: ' . $stateAll . PHP_EOL . $request;
    }

    public function stateSmartWatering()
    {
        $watering = new WateringHelper();
        // in WateringLogic
        return $watering->wateringState();
    }

    public function stateSensors()
    {
        $mqtt = new MqttHelper();
        $request = $mqtt->sensorStatus('alice');
        return $request;
    }

    public function startScheduleWatering()
    {
        Schedule::aliceStartScheduleWatering();
        return 'Запущен цикл автополива.';

    }

    public function stopScheduleWatering()
    {
        // планировщик все таски полива в null
        Schedule::aliceStopScheduleWatering();
        return 'Планировщик событий остановил сценарий полива. Автополив сейчас будет отключен.';

    }

    public function hoseOn()
    {
        $watering = new WateringHelper();
        $watering->MajorOn();
        return 'Центральный клапан включен. Шланг запитан.';

    }

    public function hoseOff()
    {
        $watering = new WateringHelper();
        $watering->MajorOff();
        return 'Центральный клапан выключен. Шланг не запитан.';

    }

    public function alarmOn()
    {
        $watering = new WateringHelper();
        $watering->AlarmOn();
        return 'Все клапаны автополива аварийно отключены. Вы можете проверить это сказав: Общий статус.';

    }

}
