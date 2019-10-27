<?php

namespace App\Console\Commands;

use App\Mail\NotificationMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LogToMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $path = __DIR__ . '/../../../storage/logs/';
        $fileName = 'laravel-'. date('Y-m-d') .'.log';
        $fileLogNotify = $path . $fileName;

        if(is_file($fileLogNotify)) {
            $this->info('log is set');
            Mail::to('decole@rambler.ru')
                ->send(new NotificationMail('log in this date', 'log', $fileLogNotify));
        }

    }
}
