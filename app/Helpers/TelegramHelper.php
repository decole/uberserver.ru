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

    /**
     * TelegramHelper constructor.
     */
    public function __construct()
    {
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
            // Log telegram errors
            //TelegramLog::error($e);
            Log::channel('telegramBot')->error($e);
        }

    }

    /**
     * get updates from hook
     * @return string
     */
    public function getHook()
    {
        try {
            // Create Telegram API object
            // Add commands paths containing your custom commands
            $this->api->addCommandsPaths($this->commands_paths);
            $this->api->enableAdmins($this->admin_users);
            // Requests Limiter (tries to prevent reaching Telegram API limits)
            $this->api->enableLimiter();
            // Handle telegram webhook request
            $this->api->handle();
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            //echo $e;
            // Log telegram errors
            Longman\TelegramBot\TelegramLog::error($e);
        } catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
            // Silence is golden!
            // Uncomment this to catch log initialisation errors
            //echo $e;
        }
        return 'hook';
    }

    public function setHook()
    {
        return 'set hook';
    }

}