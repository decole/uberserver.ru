<?php

namespace App\Console\Commands;

use App\Helpers\TelegramHelper;
use App\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Telegram bot for Home Automation
 *
 * debug functions to insert automation
 * check errors from sensors
 * in one time send specific messages to specific user(s)
 *
 */
class telegramWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'telegram messenger functions';

    private $api;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->api = new TelegramHelper();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle()
    {
        /** @var Weather $acuw */
        $string = 'Сейчас ' . $this->getRusDay() . date(" d ") . $this->getRusMonth() . PHP_EOL;
        $acuweth = $this->getAcuweather();
        $string .= 'Температура ' . $acuweth->temperature . ' С`- ' . $acuweth->spec;
        $this->api->sendDecole($string);
    }

    protected function getRusDay()
    {
        $day = date("l");

        $mass['Monday']    = 'Понедельник';
        $mass['Tuesday']   = 'Вторник';
        $mass['Wednesday'] = 'Среда';
        $mass['Thursday']  = 'Четверг';
        $mass['Friday']    = 'Пятница';
        $mass['Saturday']  = 'Суббота';
        $mass['Sunday']    = 'Воскресенье';

        $isDay = str_replace($day, $mass[$day], $day);

        return $isDay;

    }

    protected function getRusMonth()
    {
        $month = date("F");

        $mass['January'] = 'Января';
        $mass['February'] = 'Февраля';
        $mass['March'] = 'Марта';
        $mass['April'] = 'Апреля';
        $mass['May'] = 'Мая';
        $mass['June'] = 'Июня';
        $mass['July'] = 'Июля';
        $mass['August'] = 'Августа';
        $mass['September'] = 'Сентября';
        $mass['October'] = 'Октября';
        $mass['November'] = 'Ноября';
        $mass['December'] = 'Декабря';

        $isMonth = str_replace($month, $mass[$month], $month);

        return $isMonth;

    }

    /**
     * get AcuWeather parsed information at now
     */
    protected function getAcuweather()
    {
        return DB::table('weather')->orderBy('date' , 'desc')->first();
    }

}
