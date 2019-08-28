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

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;

/**
 * User "/weather" command
 *
 * Get weather info for any place.
 * This command requires an API key to be set via command config.
 */
class WeatherCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'weather';

    /**
     * @var string
     */
    protected $description = 'Покажет текущую погоду в Камышине';

    /**
     * @var string
     */
    protected $usage = '/weather';

    /**
     * @var string
     */
    protected $version = '1.2.0';


    /**
     * Get weather string from weather data
     *
     * @param array $data
     *
     * @return string
     */
    private function getWeatherString(array $data)
    {
        try {
            if (!(isset($data['cod']) && $data['cod'] === 200)) {
                return '';
            }

            //http://openweathermap.org/weather-conditions
            $conditions     = [
                'clear'        => ' ☀️',
                'clouds'       => ' ☁️',
                'rain'         => ' ☔',
                'drizzle'      => ' ☔',
                'thunderstorm' => ' ⚡️',
                'snow'         => ' ❄️',
            ];
            $conditions_now = strtolower($data['weather'][0]['main']);

            return sprintf(
                'The temperature in %s (%s) is %s°C' . PHP_EOL .
                'Current conditions are: %s%s',
                $data['name'], //city
                $data['sys']['country'], //country
                $data['main']['temp'], //temperature
                $data['weather'][0]['description'], //description of weather
                isset($conditions[$conditions_now]) ? $conditions[$conditions_now] : ''
            );
        } catch (Exception $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message      = $this->getMessage();
        $chat_id      = $message->getChat()->getId();
        $text         = '';
        $temp         = null;
        $weather_spec = null;

        $page    = file_get_contents( 'http://apidev.accuweather.com/currentconditions/v1/291309.json?language=ru-ru&apikey=hoArfRosT1215' );
        $decoded = json_decode( $page, true );

        if ( is_array( $decoded ) ) {
            if ( ! empty( $decoded[0]['Temperature']['Metric']['Value'] ) ) {
                $temp = $decoded[0]['Temperature']['Metric']['Value'];
            }
            $weather_spec = $decoded[0]['WeatherText']; //."|".$decoded[0]['WeatherIcon']; // ясно, пасмурно
        }

        if($temp === null) {
            $acuweth = DB::table('weather')->orderBy('date' , 'desc')->first();
            $text .= 'Сейчас температура ' . $acuweth->temperature . ' °C, ' . $acuweth->spec . ', за ближайшие 30 мин.';
        }
        else {
            $text .= 'Сейчас температура ' . $temp . ' °C, ' . $weather_spec . ', согласно сайту acuweather.com';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
