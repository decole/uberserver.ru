<?php

namespace App\Console\Commands;

use DateInterval;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Whoops\Exception\ErrorException;
use Carbon\Carbon;

/**
 * Command line interface to manage and runs scheduled tasks.
 */
class Schedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule {action : сообщение}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'schedule | run list add delete';


    /**
     * @var string Default action.
     */
    public $defaultAction = 'run';

    /**
     * @var bool (Optional) Run in verbose mode. Default: true
     */
    public $verbose;

    /**
     * @var string (Required) The Yii console command to run in the format
     * controller/action --param1=param1value --param2=param2value
     */
    public $command;

    /**
     * @var string (Optional) The interval to run this command or leave
     * blank to run it once. Input should be a string that PHP can
     * interpret as a DateTime interval i.e. 10 seconds, 1 hour, 2 days.
     * Default: null (run only once)
     */
    public $interval;

    /**
     * @var string (Optional) The date and time to begin. Default: NOW
     */
    public $nextRun;

    /**
     * @var int (Optional) ID of schedule to delete.
     */
    public $scheduleId;

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
     * @throws Exception
     */
    public function handle()
    {
        $actionCommand = $this->argument('action');

        if($actionCommand == 'run') {
            $this->actionRun();
        }
        if($actionCommand == 'list') {
            $this->actionList();
        }
        if($actionCommand == 'add') {
            $this->actionAdd();
        }
        if($actionCommand == 'delete') {
            $this->actionDelete();
        }

    }

    /**
     * Run scheduled tasks.
     */
    public function actionRun()
    {
        $schedule = \App\Schedule::all();

        if(!$schedule) {
            $this->info('No schedule models retrieved.');
            return true;
        }

        $successCount = 0;
        foreach($schedule as $single) {
            if($this->_runCommand($single)) {
                $successCount++;
            }
        }
        if( $successCount > 0 ) {
            $this->info("Successfully ran $successCount commands.");
        } else {
            $this->info("No commands executed.");
        }
        return true;
    }

    /**
     * @param $single \App\Schedule
     * @return bool
     * @throws Exception
     */
    protected function _runCommand($single) {
        if( !$single->next_run || $single->next_run == null ) {
            $this->info('Next run date for command not found. Skipping.');
            return false;
        }

        $currentDate = new \DateTime( 'NOW' );
        $nextRunDate = new \DateTime( $single->next_run );

        if($currentDate->format('U') > $nextRunDate->format('U')) {
            $this->info('Running.');
            try {
                $this->info('Calling runAction for command \"' . $single->command . '\".');
                $single->next_run = null;
                $single->save();

                $argvCommand = explode(' ', $single->command);
                $route = $argvCommand[0];
                unset($argvCommand[0]);
                $arrayOptions = [];
                if(!empty($argvCommand)) {
                    foreach ($argvCommand as $argvCommandValue) {
                        $argvCommandArray = explode('=', $argvCommandValue);
                        $arrayOptions[str_replace([' ', '-'], '', $argvCommandArray[0])] = $argvCommandArray[1];
                    }
                }
                $exitCode = $this->call($route, $arrayOptions);

            } catch (ErrorException $e) {
                $this->info("Running command encountered an error.\n".$e->getMessage());
            }
            $this->info('exitCode: ' . $exitCode); // take exit code to check is ok
            if($exitCode) {
                $this->info('Schedule end method failed.');
                return false;
            }

            $lastRunDate = new DateTime('NOW');
            $single->last_run = $lastRunDate->format('Y-m-d H:i:s');
            if($single->interval !== null && $single->interval !== '') {
                $interval = DateInterval::createFromDateString( $single->interval );
                $nextRunDate = $lastRunDate->add( $interval );
                $single->next_run = $nextRunDate->format('Y-m-d H:i:s');
            }

            $single->save();

            return true;
        } else {
            $this->info('Next run date for command is after current date. Skipping.');
            return false;
        }

    }
    /**
     * Add new scheduled task.
     */
    public function actionAdd()
    {
        if(!$this->command) {
            $this->command = $this->ask('Enter the Laravel console command to run in the format controller/action --param1=param1value --param2=param2value:');
        }

        if(!$this->interval) {
            $this->interval = $this->ask('Enter the interval to run this command or leave blank to run it once. Input should be a string that PHP can interpret as a DateTime interval i.e. 10 seconds, 1 hour, 2 days:');
        }
        if(!$this->nextRun) {
            $this->nextRun = $this->ask('Enter the date and time to begin:', date('d-m-Y H:i:s'));
        }


        $model = new \App\Schedule();
        $model->command = $this->command;
        $model->interval = $this->interval;
        $model->next_run = Carbon::now()->toDateTimeString();
        $model->last_run = null;

        if($model->save()) {
            $this->info('Scheduled command successfully save.');
        } else {
            $this->info('Added scheduled command failed.');
        }

        return true;
    }
    /**
     * List currently scheduled tasks.
     */
    public function actionList() {
        $schedule = \App\Schedule::all();
        if(!$schedule) {
            $this->info('No schedule records found.');
            return true;
        }
        $this->printTableRow(['ID', 'Command', 'Interval', 'Last Run', 'Next Run']);
        $this->printEmptyRow();
        foreach($schedule as $single) {
            $this->printTableRow([$single->id, $single->command, $single->interval, $single->last_run, $single->next_run]);
            $this->printEmptyRow();
        }
        return true;
    }
    /**
     * Delete scheduled task.
     */
    public function actionDelete() {
        if($this->scheduleId === null) {
            $this->actionList();
            $this->scheduleId = $this->ask('Enter ID of the scheduled task to delete:');
            // $this->prompt('Enter ID of the scheduled task to delete:',['required'=>true]);
        }
        $model = \App\Schedule::find(intval($this->scheduleId));
        if(!$model) {
            $this->info('Schedule with ID ' . $this->scheduleId . ' not found.');
            return false;
        }
        $question = $this->ask('Are you sure you want to permanently delete this record? Y/N');
        if($question == 'N' ?? $question == 'n' ) {
            $this->info('Delete aborted by user.');
            return true;
        }
        if($question == 'Y' ?? $question == 'y' ){
            if (\App\Schedule::destroy($this->scheduleId)) {
                $this->info('Schedule with ID ' . $this->scheduleId . ' successfully deleted.');
                return true;
            } else {
                $this->info('Schedule with ID ' . $this->scheduleId . ' was not deleted.');
                return false;
            }
        }
    }

    public function printTableRow($vals, $cellChars = 20, $color = false) {
        $lastIndex = count($vals) - 1;
        $nextRow = [];
        $printNextRow = false;
        foreach($vals as $key => $val) {
            $len = strlen($val);
            if($len == $cellChars) {
                $formattedVal = $val;
                $nextRow[] = '';
            } elseif($len > $cellChars) {
                $formattedVal = substr($val, 0, $cellChars);
                $nextRow[] = substr($val, $cellChars);
                $printNextRow = true;
            } elseif($len < $cellChars) {
                $formattedVal = str_pad($val, $cellChars, ' ', STR_PAD_BOTH);
                $nextRow[] = '';
            }
            if($color) {
                $formattedVal = $this->ansiFormat($formattedVal, $color);
            }
            echo $formattedVal;
            if($key !== $lastIndex) {
                echo " | ";
            }
        }
        echo "\n";
        if($printNextRow) {
            $this->printTableRow($nextRow, $cellChars, $color);
        }
    }

    public function printEmptyRow() {
        $this->printTableRow(['--------------------', '--------------------', '--------------------', '--------------------', '--------------------']);
    }

}
