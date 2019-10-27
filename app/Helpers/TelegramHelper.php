<?php
namespace App\Helpers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * This class make logic on site from commands Telegram
 */
class TelegramHelper extends BaseController
{
    private $bot_username;
    private $token;
    public  $telegram;
    public  $users;
    private $commands_paths;
    private $admin_users;
    private $mysql_credentials;
    private $hookUrl;

    /**
     * TelegramHelper constructor.
     */
    public function __construct()
    {
        $this->hookUrl = env('TELEGRAM_HOOK_URL');
        $this->bot_username = env('TELEGRAM_BOT_NAME');
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->users = [
            'decole' => env('DECOLE_TELEGRAM_ID'),
            'panterka' => env('PANTERKA_TELEGRAM_ID'),
        ];
        $this->admin_users = [
            env('DECOLE_TELEGRAM_ID'), // Decole
        ];
        $this->commands_paths = [
            __DIR__ . '/../TelegramCommands/',
        ];
        $this->mysql_credentials = [
            'host'     => env('DB_HOST'),
            'user'     => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
        ];

        try {
            $this->telegram = new Telegram($this->token, $this->bot_username);
        } catch (TelegramException $e) {
            $this->telegram = false;
        }

    }

    /**
     * Using on commands and controllers
     *
     * @param $text
     * @param $chatId
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function send($text, $chatId)
    {
        try {
            return Request::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (TelegramException $e) {
            return $e;
        }

    }

    /**
     * Send by specific user
     *
     * @param $text
     * @param string $user
     * @return bool|mixed
     * @throws TelegramException
     */
    public function sendByUser($text, $user = 'decole')
    {
        if(empty($this->users[$user])) {
            return false;
        }

        return $this->send($text, $this->users[$user]);

    }

    /**
     * Sending by Decole
     *
     * @param $text
     * @param string $user
     * @return bool
     * @throws TelegramException
     */
    public function sendDecole($text, $user = 'decole')
    {
        return $this->sendByUser($text, $user);

    }

    /**
     * @throws TelegramException
     */
    public function getUpdates()
    {
        try {
            /** @var Telegram $telegram */
            $telegram = $this->telegram;
            $telegram->setCommandConfig('weather', ['owm_api_key' => 'hoArfRosT1215']);
            $telegram->addCommandsPaths($this->commands_paths);
            $telegram->enableAdmins($this->admin_users);
            $telegram->useGetUpdatesWithoutDatabase();
            $telegram->enableLimiter();
            $server_response = $telegram->handleGetUpdates();
            if ($server_response->isOk()) {
//                $update_count = count($server_response->getResult());
//                Log::channel('telegramBot')->info(
//                    date('Y-m-d H:i:s', time())
//                    . ' - Processed '
//                    . $update_count
//                    . ' updates'
//                );
            } else {
                echo date('Y-m-d H:i:s', time()) . ' - Failed to fetch updates' . PHP_EOL;
                Log::channel('telegramBot')->info($server_response->printError());
            }
        } catch (TelegramException $e) {
            echo $e->getMessage();
            Log::channel('telegramBot')->error($e);
        }

    }

    /**
     * get updates from hook
     *
     * @param Request $request
     * @return string
     */
    public function getHook()
    {
        try {
            /** @var Telegram $telegram */
            $telegram = $this->telegram;
            $telegram->setCommandConfig('weather', ['owm_api_key' => 'hoArfRosT1215']);
            $telegram->addCommandsPaths($this->commands_paths);
            $telegram->enableAdmins($this->admin_users);
            $telegram->useGetUpdatesWithoutDatabase();
            $telegram->enableLimiter();
            $telegram->handle();
            //$server_response = $telegram->handle();
            //Log::channel('telegramBot')->error($server_response->getResult());
        } catch (TelegramException $e) {
            echo $e->getMessage();
            // Log telegram errors
            //TelegramLog::error($e);
            Log::channel('telegramBot')->error($e);
        }
        return 'web-hook';

    }

    /**
     * set webhook from telegram.org
     *
     * @return string
     * @throws TelegramException
     */
    public function setHook()
    {
        // Create Telegram API object
        $telegram = $this->telegram;
        // Set webhook
        $result = $telegram->setWebhook($this->hookUrl);
        if ($result->isOk()) {
            return $result->getDescription();
        }
        return 'set hook';

    }

}