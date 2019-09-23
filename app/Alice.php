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

    /**
     * @param $apiRequestArray
     */
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
     * Состояние датчиков
     *
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

    /**
     * Состояние полива, на текущий момент
     *
     * @return string
     */
    public function stateSmartWatering()
    {
        $watering = new WateringHelper();
        // in WateringLogic
        return $watering->wateringState();
    }

    /**
     * Состояние сенсоров
     *
     * @return string
     */
    public function stateSensors()
    {
        $mqtt = new MqttHelper();
        $request = $mqtt->sensorStatus('alice');

        return $request;

    }

    /**
     * Запуск восстановления сценариев планировщика задач. Часто бывает когда выключают электричество, ПК через ИБП
     * опрашивает отключенные датчкики и null-ит все задачи по поливу. Приходится заново запускать.
     *
     * @return string
     */
    public function startScheduleWatering()
    {
//        Schedule::aliceStartScheduleWatering();
//        return 'Запущен цикл автополива.';
        return 'Нынче не сезон. Система не поддерживается';

    }

    /**
     * Останов сценариев полива. Бывает что  жене нужно прямо здесь и сейчас сделать то, что не возможно.
     *
     * @return string
     */
    public function stopScheduleWatering()
    {
        // планировщик все таски полива в null
//        Schedule::aliceStopScheduleWatering();
//        return 'Планировщик событий остановил сценарий полива. Автополив сейчас отключен.';
        return 'Нынче не сезон. Система не поддерживается';

    }

    /**
     * Включение отходящего шланга на центральном клапане. Не включать если неизвестно состояние крана шланга.
     * При закрытом кране, вода не льется через шланг и нагнетается давление на центральном клапане.
     * !! Возможна протечка.
     *
     * @return string
     */
    public function hoseOn()
    {
        $watering = new WateringHelper();
        $watering->MajorOn();

        return 'Центральный клапан включен. Шланг запитан.';

    }

    /**
     * Выключение шланга
     *
     * @return string
     */
    public function hoseOff()
    {
        $watering = new WateringHelper();
        $watering->MajorOff();

        return 'Центральный клапан выключен. Шланг не запитан.';

    }

    /**
     * Аварийный останов
     *
     * @return string
     */
    public function alarmOn()
    {
        $watering = new WateringHelper();
        $watering->AlarmOn();

        return 'Все клапаны автополива аварийно отключены. Вы можете проверить это сказав: Общий статус.';

    }

    // @Todo сделать как надо!!!
    public function lampOn()
    {
        $mqtt = new MqttHelper();
        $mqtt->post('margulis/lamp01', 'on');

        return 'Освещение включено';

    }

    // @Todo сделать как надо!!!
    public function lampOff()
    {
        $mqtt = new MqttHelper();
        $mqtt->post('margulis/lamp01', 'off');

        return 'Освещение выключено';

    }

}
