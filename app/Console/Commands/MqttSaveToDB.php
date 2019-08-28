<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use Illuminate\Console\Command;

class mqttSaveToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:save';

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
        $mqtt = new MqttHelper();
        $mqtt->saveToDB();
        $this->line('save state sensors to db');
    }
}
