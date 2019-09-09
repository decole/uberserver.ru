<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "weather".
 *
 * @property integer $id
 * @property string $temperature
 * @property string $spec
 * @property integer $date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Weather extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'weather';

    public static function getWeather()
    {
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
            $acuweth = self::orderBy('date' , 'desc')->first();
            $text .= 'Сейчас температура ' . $acuweth->temperature . ' °C, ' . $acuweth->spec;
        }
        else {
            $text .= 'Сейчас температура ' . $temp . ' °C, ' . $weather_spec . ', согласно сайту acuweather.com';
        }

        return $text;
    }
}
