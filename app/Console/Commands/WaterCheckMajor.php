<?php

namespace App\Console\Commands;

use App\Helpers\WateringHelper;
use Illuminate\Console\Command;

class WaterCheckMajor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'water:checkMajor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check major watering swift sing turn on';
    private $water;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->water = new WateringHelper();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->water->wateringCheckMajor();
        $this->info('chacked');
    }
}
