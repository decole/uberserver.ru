<?php

namespace App\Http\Controllers;

use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;


class TeleBotController extends Controller
{
    private $bot_api_key;
    private $bot_username;
    private $admin_users;
    private $commands_paths;

    /**
     * @return string
     */
    public function webhook()
    {
        $bot = new TelegramHelper();
        return $bot->getHook();
    }

    /**
     * @return string
     */
    public function setWebhook()
    {
        // https://medium.com/@xabaras/setting-your-telegram-bot-webhook-the-easy-way-c7577b2d6f72
        $bot = new TelegramHelper();
        return $bot->setHook();
//        include_once($this->commands_paths[0] . 'index.php');
//        return $this->commands_paths[0] . 'index.php';
    }

}
