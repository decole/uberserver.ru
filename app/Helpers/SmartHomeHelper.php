<?php
namespace App\Helpers;

use Illuminate\Routing\Controller as BaseController;

/**
 * This class make logic on server from commanding Smart Home
 */
class SmartHomeHelper extends BaseController
{
    /**
     * For Alice use
     */
    private $swiftLamp01 = 'margulis/lamp01';
    private $nameTopicLeakage = 'water/leakage'; // топик проверки протечки воды у главного клапана
    private $mqtt;
    private $options;


    public function __construct()
    {
        $this->mqtt = new MqttHelper();
        $this->options = $this->mqtt::listTopics();
    }

    public function swiftOn($topic): void
    {
        $mqtt = $this->mqtt;
        $options = $mqtt::listTopics();
        $mqtt->post($topic, $options[$topic]['condition']['on']);

    }

    public function swiftOff($topic): void
    {
        $mqtt = $this->mqtt;
        $options = $mqtt::listTopics();
        $mqtt->post($topic, $options[$topic]['condition']['off']);

    }

    /**
     * Stop all smart watering swifts
     */
    public function stopAll(): void
    {
        $this->swiftOff($this->swiftLamp01);
//        $this->swiftOff($this->swiftOne);
//        $this->swiftOff($this->swiftTwo);
//        $this->swiftOff($this->swiftThree);
        self::sendMessage('Все реле(выключатели) - останов всего');

    }

    /**
     * Включение лампы в пристройке
     */
    public function Lamp01On(): void
    {
        $this->swiftOn($this->swiftLamp01);
        self::sendMessage('Лампа включена.Состояние');

    }

    /**
     * Выключение лампы в пристройке
     */
    public function Lamp01Off(): void
    {
        $this->swiftOff($this->swiftLamp01);
        self::sendMessage('Лампа выключена.Состояние');

    }


    private function sendMessage($message): void
    {
        if(!empty($message)) {
            $send = new TelegramHelper();
            $send->sendByUser($message, 'decole');
        }

    }

    /**
     * For check leakage in home
     *
     * @return bool
     */
    public function checkLeakage()
    {
        $mqtt = $this->mqtt;
        $options = $this->options;
        return $mqtt->getCacheMqtt($this->nameTopicLeakage) == $options[$this->nameTopicLeakage]['condition']['normal'];

    }


}