<?php

namespace App;

use App\Helpers\WateringHelper;
use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "schedule".
 *
 * @property int     $id
 * @property string  $command
 * @property string  $interval
 * @property string  $last_run
 * @property string  $next_run
 * @property \Carbon\Carbon $created
 * @property \Carbon\Carbon $updated
 */
class Schedule extends Model
{
    protected $table = 'schedule';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    /**
     * {@inheritdoc}
     */
    public function begin() {
        $this->next_run = null;
        return $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function end() {
        $lastRunDate = new DateTime('NOW');
        $this->last_run = $lastRunDate->format('Y-m-d H:i:s');
        if($this->interval !== null && $this->interval !== '') {
            $interval = DateInterval::createFromDateString( $this->interval );
            $nextRunDate = $lastRunDate->add( $interval );
            $this->next_run = $nextRunDate->format('Y-m-d H:i:s');
        }
        return $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function add($command, $interval = null, $nextRun = null) {
        $model = new self;
        $model->command = $command;
        $model->interval = $interval;
        $model->last_run = null;
        $nextRunDate = new DateTime('NOW');
        $model->next_run = $nextRunDate->format('Y-m-d H:i:s');
        return $model->save();
    }

    /**
     * Алиса стартанет планировщик автополива
     *
     * @return string
     */
    public static function aliceStartScheduleWatering(): string
    {
        $model = new self;
        $waterLogic = new WateringHelper();
        $options = $waterLogic::listTimers();
        $timer = 0;
        foreach ($options as $task=>$parameter) {
            if($parameter['type'] === 'check') {
                $date = date('Y-m-d H:i:s');
                $model->changeTimer($parameter['id_in_db'], $date);
            }
            if($parameter['type'] === 'scenario') {
                if(!empty($parameter['time_at'])) {
                    $timer = 0;
                }
                $model->changeTimer($parameter['id_in_db'], $model->setTimer($timer, $parameter['time_at']));
                $timer += $parameter['working_minutes'];
            }
        }

        return true;
    }

    /**
     * Алиса остановит планировщик автополива
     *
     * @return string
     */
    public static function aliceStopScheduleWatering(): string
    {
        $model = new self;
        $waterLogic = new WateringHelper();
        $options = $waterLogic::listTimers();

        foreach ($options as $task=>$parameter) {
            $model->changeTimer($parameter['id_in_db'],null);
        }
        $waterLogic->stopAll();

        return 'Планировщик остановлен. Полив отсключен'.PHP_EOL;
    }

    /**
     *
     *
     * @var \App\Schedule $taskModel
     * @param $task
     * @param $date
     */
    private function changeTimer($taskId, $date): void
    {
        $taskModel = self::where(['id' => $taskId])->first();
        $taskModel->next_run = $date;
        $taskModel->save();

    }

    /**
     * changing time of this period time
     * @throws $e
     * @param $minutes
     * @param $timeAt
     * @return string
     */
    private function setTimer($minutes, $timeAt): string
    {
        if(!empty($timeAt)) {
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d ') . $timeAt);
            $this->date = $dateTime;
        }
        else {
            if (empty($timeAt)){
                $dateTime = new DateTime();
                if(!empty($this->date)) {
                    $dateTime = $this->date;
                }
            }
        }
        $now = $dateTime->getTimestamp();
        $dateTime->setTimestamp($now + $minutes*60);
        return $dateTime->format("Y-m-d H:i:s");
    }

}
