<?php

namespace App\Http\Controllers;


use App\Helpers\MqttHelper;
use App\MqttPayload;
use App\Relays;
use App\Weather;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SensorsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @var Weather $acuweather
     */
    public function showSensors(Request $request)
    {
        $timeLineW = Weather::max('date');
        $acuweather = Weather::where(['date' => "$timeLineW"])->first();

        return view('sensors', [
                'page_title' => 'Данные сенсоров',
                'sensors' => [
                    'margulis_temperature'       => $this->verifiMqttData('margulis/temperature'),
                    'margulis_humidity'          => $this->verifiMqttData('margulis/humidity'),
                    'holl_temperature'           => $this->verifiMqttData('holl/temperature'),
                    'holl_humidity'              => $this->verifiMqttData('holl/humidity'),
                    'underflor_temperature'      => $this->verifiMqttData('underflor/temperature'),
                    'underflor_humidity'         => $this->verifiMqttData('underflor/humidity'),
                    'underground_temperature'    => $this->verifiMqttData('underground/temperature'),
                    'underground_humidity'       => $this->verifiMqttData('underground/humidity'),
                    'home_kitchen_temperature'   => $this->verifiMqttData('home/kitchen/temperature'),
                    'home_restroom_temperature'  => $this->verifiMqttData('home/restroom/temperature'),
                    'home_hall_temperature'      => $this->verifiMqttData('home/hall/temperature'),
                ],
                'acuweather' => $acuweather,
                'max' => $timeLineW,
                'sidebar'    => $request->sideBarComponent,
            ]
        );

    }

    public function chartShow(Request $request)
    {
        return view('charts', [
                'page_title' => 'Данные сенсоров',
                'sidebar'    => $request->sideBarComponent,
            ]
        );

    }

    /**
     * Для страницы с температурами отправляет температуры и влажности
     *
     * @param Request $request
     * @return mixed|string
     */
    public function stateSensorsGet(Request $request)
    {
        return self::verifiMqttData($request->topic);

    }

    /**
     * Вытаскивает значение из кэша иначе из БД
     *
     * @param $topic
     * @return mixed|string
     */
    private function verifiMqttData($topic)
    {
        $cache = new MqttHelper();
        $data = null;
        $data = $cache->getCacheMqtt($topic);
        if($data === null) {
            $query = MqttPayload::where(['topic' => $topic])->orderByDesc('created_at', 'id')->first();
            if(isset($query->payload)) {
                return MqttPayload::where(['topic' => $topic])->orderByDesc('created_at', 'id')->first()->payload . ' - no cache';
            }
            return null;
        }
        return $data;


    }

    /**
     * Для страницы с водой, отправляет статус реле / клапанов
     *
     * @param Request $request
     * @return mixed|string
     */
    public function stateRelaysGet(Request $request)
    {
        $cache = new MqttHelper();
        if($cache->getCacheMqtt($request->topic) === null) {
            return Relays::where(['id' => $request->topic])->first()->state;
        }
        return $cache->getCacheMqtt($request->topic);

    }

    public function stateRelaysSet(Request $request)
    {
        $relay = $request->a;
        $state = $request->r;
        $stateTranslate = [
            '0' => 'off',
            '1' => 'on',
        ];

        if ($relay != null && ($state == 1 || $state == 0)) {

            $relay = $relay ?? null; // number of sensor
            $state = $state ?? null; // status relay  0 - off, 1 - on

            if($relay !== null && $state !== null){
                Relays::where('id', $relay)->update(['state' => $state]);
                $model = Relays::where('id', $relay)->first();
                $state = MqttHelper::listTopics()[$model->topic]['condition'][$stateTranslate[$state]];
                $this->changeRelay($model->topic,$state);

                return $state;
            }
            var_dump($request);
        }

        return 'error';

    }

    public function stateLeakageGet(Request $request)
    {
        $mqtt = new MqttHelper();
        $options = $mqtt::listTopics();
        $stateLeakage = $mqtt->getCacheMqtt($request->topic);
        if($options[$request->topic]['condition']['normal'] == $stateLeakage)
        {
            return 0;
        }
        else {
            return 1;
        }

    }


    public function emergencySensor(Request $request): string
    {
        $action = $request->action;
        $topic =  $request->topic;

        if($action !== null && $topic !== null) {
            $mqtt = new MqttHelper();
            if($action === 'on') {
                $mqtt->post($topic, 1);
                return '1';
            }
            if($action === 'off') {
                $mqtt->post($topic, 0);
                return '0';
            }
            if($action === 'state') {
                return $mqtt->getCacheMqtt($topic);
            }
        }

        return '0';

    }

    private function changeRelay($topic, $state): void
    {
            $mqtt = new MqttHelper();
            $mqtt->post($topic, $state);

    }

    /**
     * @param Request $request
     * @return array
     */
    public function chartShowGet(Request $request)
    {
        $topic = $request->topic;
        $date = $request->date;

        if($date == 'current') {
            $date = date('Y-m-d');
        }

        $mqttData = DB::table('mqtt_payload')
            ->whereDate('created_at', $date)
            ->where('topic', $topic)
            ->get();

        $weatherData = DB::table('weather')
            ->whereDate('created_at', $date)
            ->get();

        $mqttData = $mqttData->toArray();
        $weatherData = $weatherData->toArray();

        $chart = [];
        $min = '';
        foreach ($mqttData as $mqtt) {
            $timeMqtt = date_timestamp_get(date_create($mqtt->datetime));
            if(!empty($weatherData)) {
                foreach ($weatherData as $key => $acuweather) {
                    $timeAcuweather = date_timestamp_get(date_create($acuweather->date));
                    if ($timeMqtt > $timeAcuweather) {
                        $min = $acuweather->temperature;
                    }
                    if ($timeMqtt < $timeAcuweather) {
                        if (empty($min)) {
                            $min = $acuweather->temperature;
                        }
                        $chart[$mqtt->datetime] = [
                            'mqtt' => $mqtt->payload,
                            'acuweather' => $acuweather->temperature,
                        ];
                        break;
                    }
                }
            }
            else {
                $chart[$mqtt->datetime] = [
                    'mqtt' => $mqtt->payload,
                    'acuweather' => '',
                ];
            }
        }
        $template = [];

        $mqttValues = [];
        $weatherValues = [];
        foreach ($chart as $valueChart) {
            $mqttValues[] = $valueChart['mqtt'];
            $weatherValues[] = $valueChart['acuweather'];
        }

        $template['labels'] = array_keys($chart); // ["$topic"]
        $template['datasets'] = [
            [
                'data' => array_values($mqttValues),
                'label' => 'Mqtt sensor',
                'fill'=>false,
                'borderColor'=>'rgb(75, 192, 192)',
                'lineTension'=>0.1,
            ],
            [
                'data' => array_values($weatherValues),
                'label' => 'AcuWeather',
                'fill'=>false,
                'borderColor'=>'rgb(114, 151, 151)',
                'lineTension'=>0.1,
            ],
        ];

        return $template;

    }

}
