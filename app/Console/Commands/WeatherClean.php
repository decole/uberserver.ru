<?php

namespace App\Console\Commands;

use App\Weather;
use Illuminate\Console\Command;

class weatherClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear table weather';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $weather = new Weather();
        $weather::truncate();
        $this->line('table weather is optimised');
    }
}
