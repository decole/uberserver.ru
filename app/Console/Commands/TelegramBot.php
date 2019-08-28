<?php

namespace App\Console\Commands;

use App\Helpers\TelegramHelper;
use Illuminate\Console\Command;

class TelegramBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram Bot start';

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
        $bot = new TelegramHelper();
        while (true) {
            $bot->getUpdates();
            sleep(7);
        }
    }
}
