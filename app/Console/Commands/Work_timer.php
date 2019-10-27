<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use App\WorkTimer;
use Illuminate\Console\Command;

class Work_timer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timer:start';

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
     * @var WorkTimer $model
     * @var WorkTimer $works
     * @var WorkTimer $work
     * @return void
     */
    public function handle()
    {
//        $this->info('start command');
        $works = WorkTimer::all();
        foreach ($works as $work) {
//            $this->info('work ' . $work->name);

            if($work->active === 1) {
                $timeNow = time();
                $timeEnd = strtotime($work->time_end);
//                $this->info('time now:' . $timeNow . ' time end:' . $timeEnd);

                if($timeEnd > $timeNow) {
//                    $this->info('timer is continued, get topic is on');
                    self::postMqtt($work->topic, $work->command_on);
                }
                if($timeNow > $timeEnd) {
//                    $this->info('timer is ending, get topic off');
                    $model = WorkTimer::where('id', $work->id)->first();
                    $model->active = 0;
                    $model->save();
                    self::postMqtt($work->topic, $work->command_off);
                }
            }
        }

//        $this->info('end command');
    }

    private function postMqtt($topic, $command)
    {
        $mqtt = new MqttHelper();
        $mqtt->post($topic, $command);
        $this->info($topic . ' - ' . $command);
    }
}
