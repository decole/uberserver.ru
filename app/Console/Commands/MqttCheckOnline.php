<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\MqttHelper;

class MqttCheckOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:checkOnline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check online controllers';

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
     * Check online controllers
     *
     * @return void
     */
    public function handle()
    {
        $mqtt = new MqttHelper();
        $check = $mqtt->checkOnline();
        $options = $mqtt::listTopics();
        $message = null;

        foreach($check as $topic=>$value) {
            if($value === 'offline') { // offline
                $message .= $topic.' - is offline'.PHP_EOL;
            }
        }
        if($message !== null){
            $mqtt->mailing($message, $options);
        }
    }
}
