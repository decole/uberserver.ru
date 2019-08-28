<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save weather in DB';

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
        $temp         = "Null";
        $weather_spec = "Null";

        $page    = file_get_contents( 'http://apidev.accuweather.com/currentconditions/v1/291309.json?language=ru-ru&apikey=hoArfRosT1215' );
        $decoded = json_decode( $page, true );
        if ( is_array( $decoded ) ) {
            if ( ! empty( $decoded[0]['Temperature']['Metric']['Value'] ) ) {
                $temp = $decoded[0]['Temperature']['Metric']['Value'];
            }
            $weather_spec = $decoded[0]['WeatherText'];
        }

        $customer              = new \App\Weather();
        $customer->temperature = $temp;
        $customer->spec        = $weather_spec;
        $customer->date        = date( "Y-m-d H:i:s" );
        if($customer->save()) {
            $this->line('weather saved');
        }
    }
}
