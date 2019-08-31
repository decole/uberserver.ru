<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('index', [
            'page_title' => 'Start Page',
            'speech'     => $speech,
            'actions'    => $driving,
        ]);
    }
}
