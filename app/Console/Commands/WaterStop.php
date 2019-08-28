<?php

namespace App\Console\Commands;

use App\Helpers\WateringHelper;
use Illuminate\Console\Command;

class WaterStop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'water:stop';

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
        $mqtt = new WateringHelper();
        $mqtt->stopAll();
        $this->info('all watering swifts is off');
    }
}
