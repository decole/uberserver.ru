<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Hello extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hello {option}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'memcached list / set';

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
        $optionCommand = $this->argument('option');
        if($optionCommand == 'list') {
            $this->info('Memcached list !');
            $key = $this->ask('What is KEY memcached ?');

            $this->info(Cache::get($key));
        }
        if($optionCommand == 'set') {
            $key = $this->ask('What is KEY memcached ?');
            $value = $this->ask('What is VALUE memcached ?');
            $expiresAt = Carbon::now()->addMinutes(60);

            Cache::put($key, $value, $expiresAt);
        }

    }

    public function fire()
    {
        $this->info('Fire !');
    }

    
}
