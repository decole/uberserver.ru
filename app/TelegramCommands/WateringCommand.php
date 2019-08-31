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

use App\Helpers\MqttHelper;
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
class WateringCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'watering';

    /**
     * @var string
     */
    protected $description = 'Данные по сенсорам';

    /**
     * @var string
     */
    protected $usage = '/watering';

    /**
     * @var string
     */
    protected $version = '0.0.1';

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
        $text = 'Умный полив:'.PHP_EOL.PHP_EOL
            .'/majorOn'.PHP_EOL.PHP_EOL
            .'/majorOff'.PHP_EOL.PHP_EOL
            .'/oneOn'.PHP_EOL.PHP_EOL
            .'/oneOff'.PHP_EOL.PHP_EOL
            .'/twooOn'.PHP_EOL.PHP_EOL
            .'/twooOff'.PHP_EOL.PHP_EOL
            .'/twooOff'.PHP_EOL.PHP_EOL
            .'/threeOn'.PHP_EOL.PHP_EOL
            .'/threeOff'.PHP_EOL.PHP_EOL . ' laravel';

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
