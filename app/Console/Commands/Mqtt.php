<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use Illuminate\Console\Command;

class mqtt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starting parser on mqtt protocol';

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
     * Starting parser in console.
     *
     * @return void
     */
    public function handle()
    {
        $mqtt = new MqttHelper();
        $mqtt->listen();

    }

}
