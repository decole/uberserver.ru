<?php

namespace App\Console\Commands;

use App\Helpers\TelegramHelper;
use Illuminate\Console\Command;

class TelegramSendDecole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send {message : сообщение}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'telegram:send < message > to Decole';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle()
    {
        $telegram = new TelegramHelper();
        $message = $this->argument('message');
        $telegram->sendDecole($message);
    }
}
