<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;

/**
 * User "/date" command
 *
 * Shows the date and time of the location passed as the parameter.
 *
 * A Google API key is required for this command, and it can be set in your hook file:
 * $telegram->setCommandConfig('date', ['google_api_key' => 'your_api_key']);
 */
class DateCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'date';

    /**
     * @var string
     */
    protected $description = 'Время и дата в Волгоградской области';

    /**
     * @var string
     */
    protected $usage = '/date';

    /**
     * @var string
     */
    protected $version = '1.4.1';

    /**
     * Guzzle Client object
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

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
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id  = $message->getChat()->getId();

        $text = 'Текущая дата: ' . date(" d ") . $this->getRusMonth() . ', ' . $this->getRusDay() . ' laravel';

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
