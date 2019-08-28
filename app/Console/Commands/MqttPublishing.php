<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use Illuminate\Console\Command;

class MqttPublishing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:pub {topic : топик} {message : сообщение}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishing on mosquitto broker | example mqtt:pub topic message';

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
     * Sending data to topic on mqtt protocol
     * @param $topic $data
     * @return void
     */
    public function handle()
    {
        $mqtt = new MqttHelper();
        $topic = $this->argument('topic');
        $data = $this->argument('message');
        $mqtt->post($topic, $data);
    }
}
