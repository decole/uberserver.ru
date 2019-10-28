<?php

namespace App\Console\Commands;

use App\Helpers\MqttHelper;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SiteInfo;
use App\User;
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
        $works = WorkTimer::all();
        foreach ($works as $work) {

            if($work->active === 1) {
                $timeNow = time();
                $timeEnd = strtotime($work->time_end);

                if($timeEnd > $timeNow) {
                    self::postMqtt($work->topic, $work->command_on);
                }
                if($timeNow > $timeEnd) {
                    $model = WorkTimer::where('id', $work->id)->first();
                    $model->active = 0;
                    $model->save();
                    $user = User::find(1);
                    $user->notify(new SiteInfo('таймер ' . $model->name . ' выключен'));
                    self::postMqtt($work->topic, $work->command_off);
                }
            }
        }

    }

    private function postMqtt($topic, $command)
    {
        $mqtt = new MqttHelper();
        $mqtt->post($topic, $command);
        $this->info($topic . ' - ' . $command);
    }
}
