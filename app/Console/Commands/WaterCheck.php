<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use App\Helpers\WateringHelper;
use Illuminate\Console\Command;

class WaterCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'water:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check watering swifts on wrong terminating operations';

    private $mqtt;
    private $options;
    private $water;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->mqtt = new MqttHelper();
        $this->options = $this->mqtt::listTopics();
        $this->water = new WateringHelper();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle()
    {
        $mqtt = $this->mqtt;
        $options = $this->options;
        foreach ($options as $topic=>$option) {
            if($option['type'] === 'swift') {
                $stateTopic = $mqtt->getCacheMqtt($topic);
                $checkStateTopic = $mqtt->getCacheMqtt($option['checkTopic']);
                if($stateTopic !== $checkStateTopic) {
                    // если есть разница в состояниях топиков полива, то подождать 3 минуты и после сравнить, и если
                    // разница состояний осталась, между топиком и топиком проверки состояния, то отправить в телеграм
                    $mqtt->checkAlarmTopic($topic, $option['checkTopic']);
                }
                // проверка всех топиков в checkStateTopicAlarm на просроченность (удаление просроченных топиков)
                $mqtt->checkOldMemcachedAlarmTopics();
            }
        }
    }
}
