<?php

namespace App\Console\Commands;

use App\MqttPayload;
use Illuminate\Console\Command;

class mqttClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean table mqtt on specifed dates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /*
        $date = new \DateTime();
        $start = $date->sub(new \DateInterval('P3M'));
        $start = $start->setDate($start->format('Y'), $start->format('m'), 1)->setTime(0, 0, 0);
        $end = $start->add(new \DateInterval('P1M'));
        Mqtt::deleteAll(['AND', ['>=', 'datetime', $start], ['<', 'datetime', $end]]);
        */
        /*
         * - удалить весь месяц (с первого по последнее число), за третий месяц назад от текущего.
         * например сегодня 20 ноября, удаляются данные 1-31 августа
        */
        // $datas = Mqtt::find()->where(['topic' => $topic, 'DATE(`datetime`)' => $date])->all();
        $afterSecondMonth = $this->AfterDate(date('m'))['month'];
        $afterTherdMonth = $this->AfterDate($afterSecondMonth)['month'];
        // @Todo сделать аналог deleteAll
        MqttPayload::deleteAll(['AND',
            ['<', 'datetime', date('Y-'.$afterSecondMonth.'-01 00:00:00')],
            ['>=', 'datetime', date('Y-'.$afterTherdMonth.'-01 00:00:00')]
//            ['>=', 'datetime', date('2018-'.$afterTherdMonth.'-01 00:00:00')]
        ]);

    }

    /**
     * @param $month
     * @return array
     * is take befor month - days in month, befor month, and year
     */
    public function AfterDate($month)
    {
        $year = date('Y');
        if ($month > 1) {
            $month = $month - 1;
        }
        if ($month === 1) {
            $month = 12;
            $year = $year - 1;
        }
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return ['days' => $days,'month' => $month, 'year' => $year];
    }
}
