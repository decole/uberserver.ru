<?php

namespace App\Console\Commands;

use App\Helpers\WateringHelper;
use Illuminate\Console\Command;

class WaterOn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'water:on {name : клапан}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'turn on water swift  - water:on < number >  is 0 - major, 1, 2, 3 ';
    protected $water;
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
        $name = $this->argument('name');

        if($name == 0 && !($this->water->checkLeakage() === false)) {
            $this->water->MajorOn();
        }
        if($name == 1 && !($this->water->checkLeakage() === false)) {
            $this->water->OneOn();
        }
        if($name == 2 && !($this->water->checkLeakage() === false)) {
            $this->water->TwoOn();
        }
        if($name == 3 && !($this->water->checkLeakage() === false)) {
            $this->water->ThreeOn();
        }

    }

}
