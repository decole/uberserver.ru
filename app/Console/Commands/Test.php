<?php

namespace App\Console\Commands;

use App\AliceSecure;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'testing new some function';

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
        $this->info('test registring user in AliceSecure');
        $id = '13D65C01F8B51512AF66DAC3DCAE2F893A9D3E8B0851A6BF9C44EB512D48F065';

//        $security = new AliceSecure();
//        $security->registerUser($userId);
        $admin = AliceSecure::isAdmin($id);
        print_r($admin);
    }
}
