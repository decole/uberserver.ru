<?php

namespace App\Http\Controllers;

use App\Helpers\MqttHelper;
use App\Mail\NotificationMail;
use App\Notifications\SecurityInfo;
use App\Notifications\SensorsInfo;
use App\Notifications\SiteInfo;
use App\Notifications\SystemInfo;
use App\Relays;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('sidebar');
    }

    public function index(Request $request)
    {
        $action = 'none';
        $speech = [
            'Отключен VK Bot (граббинг сообщений группы) более не поддерживается.',
            'Есть Telegram Bot - отправка сообщений.',
            'Подключены Arduino модули - температурные датчики, реле, датчик давления.',
            'Так же прикручен голосовой помощник - Яндекс Алиса',
        ];
        $driving = [
            'ard' => 'Что там с Ардуинкой?',
            'telebot' => 'Телеграм Бот ?',
            'vkbot' => 'VK Bot ?',
        ];

        if($request->act) {
            switch ($request->act){
                case 'ard':
                    $speech  = [
                        'Сервис Ардуинки собирает данные с контроллеров',
                        'Есть 4 абстрактных кнопки изменяющие статус реле',
                        'Так же есть страница с отображением данных сенсоров.',
                    ];
                    $driving = [
                        'top' => 'на главную',
                    ];
                    break;
                case 'telebot':
                    $speech  = [
                        'Да, есть сторонняя реализация, переделан на более новый движок бота.',
                        'Работает это чудо для уведомления особых пользователей о разных ситуациях в доме и 
                            на сервере.',
                    ];
                    $driving = [
                        'top' => 'на главную',
                    ];
                    break;
                case 'vkbot':
                    $speech  = [
                        'Да, есть бот, но он сейчас не активен ввиду того, что популярней и быстрее передать 
                            сообщение по телеграм каналу',
                    ];
                    $driving = [
                        'top' => 'на главную',
                    ];
                    break;
            }
        }

//        $user = User::find(1);
//        $user->notify(new SiteInfo('message to notify'));

//      Пример отправки сообщений на почту
//      Mail::to('decole@rambler.ru')
//          ->send(new NotificationMail('lol', 'alarm'));

//      Пример использования нотификаций где угодно
//      Notification::send($user, new SecurityInfo('lol', 'alarm'));

//      Или через контроллер из юзера
//      $user = User::find(1);
//      $user->notify(new SystemInfo('lol', 'alarm'));

        return view('index', [
            'page_title' => 'Start Page',
            'speech'     => $speech,
            'actions'    => $driving,
            'sidebar'    => $request->sideBarComponent,
        ]);
    }

    /**
     * Page shown watering swifts
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHomeSwifts(Request $request)
    {
        $mqtt = new MqttHelper();
        $options = $mqtt::listTopics();
        $sensors = [];

        foreach ($options as $key=>$value) {
            if($value['format'] == 'home' && $value['type'] == 'swift') {
                $sensors[$key]['name'] = $value['sensorName'];
                $sensors[$key]['topic'] = $key;
                $sensors[$key]['id'] = $value['RelayID'];
                $sensors[$key]['state'] = 'off';
            }
        }

        return view('smarthome', [
                'page_title' => 'Умный дом',
                'ralays'     => $sensors,
                'sidebar'    => $request->sideBarComponent,
            ]
        );

    }

}
