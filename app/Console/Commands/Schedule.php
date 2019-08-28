<?php

namespace App\Console\Commands;

use App\Helpers\WateringHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Whoops\Exception\ErrorException;

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
        $this->info('start');
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

    protected function _runCommand($single) {
        if( !$single->next_run || $single->next_run == null ) {
            $this->info('Next run date for command not found. Skipping.');
            return false;
        }

        $nextRunDate = new \DateTime( $single->next_run );
        $currentDate = new \DateTime('NOW');
        if($currentDate > $nextRunDate) {
            $this->info('Next run date for command is before current date. Running.');
            try {
                $this->info('Calling runAction for command \"' . $single->command . '\".');
                $exitCode = Artisan::call($single->command);
            } catch (ErrorException $e) {
                $this->info("Running command encountered an error.\n".$e->getMessage());
            }
            $this->info('exitCode: ' . $exitCode); // take exit code to check is ok
            if($exitCode) {
                $this->info('Schedule end method failed.');
            }
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
            $this->command = $this->prompt('Enter the Yii console command to run in the format controller/action --param1=param1value --param2=param2value:',['required'=>true]);
        }

        if(!$this->interval) {
            $this->interval = $this->prompt('Enter the interval to run this command or leave blank to run it once. Input should be a string that PHP can interpret as a DateTime interval i.e. 10 seconds, 1 hour, 2 days:');
        }
        if(!$this->nextRun) {
            $this->nextRun = $this->prompt('Enter the date and time to begin:', ['default'=>null]);
        }

        if($result = Schedule::add($this->command, $this->interval, $this->nextRun)) {
            $this->log('Scheduled command successfully save.', true);
        } else {
            $this->warning('Added scheduled command failed.', true);
            var_dump($result->getErrors());
        }

        return Controller::EXIT_CODE_NORMAL;
    }
    /**
     * List currently scheduled tasks.
     */
    public function actionList() {
        $schedule = Schedule::find()->all();
        if(!$schedule) {
            $this->warning('No schedule records found.');
            return Controller::EXIT_CODE_NORMAL;
        }
        $this->printTableRow(['ID', 'Command', 'Interval', 'Last Run', 'Next Run']);
        $this->printEmptyRow();
        foreach($schedule as $single) {
            $this->printTableRow([$single->id, $single->command, $single->interval, $single->last_run, $single->next_run]);
            $this->printEmptyRow();
        }
        return Controller::EXIT_CODE_NORMAL;
    }
    /**
     * Delete scheduled task.
     */
    public function actionDelete() {
        if($this->scheduleId === null) {
            $this->actionList();
            $this->scheduleId = $this->prompt('Enter ID of the scheduled task to delete:',['required'=>true]);
        }
        $model = Schedule::findOne(intval($this->scheduleId));
        if(!$model) {
            throw new Exception('Schedule with ID ' . $this->scheduleId . ' not found.');
            return Controller::EXIT_CODE_ERROR;
        }
        if(!$this->confirm('Are you sure you want to permanently delete this record?')) {
            $this->log('Delete aborted by user.');
            return Controller::EXIT_CODE_NORMAL;
        }
        if($model->delete()) {
            $this->warning('Schedule with ID ' . $this->scheduleId . ' successfully deleted.');
            return Controller::EXIT_CODE_NORMAL;
        } else {
            $this->log('Schedule with ID ' . $this->scheduleId . ' was not deleted.');
            return Controller::EXIT_CODE_ERROR;
        }
    }
    public function warning($message, $force = false) {
        if($this->verbose || $force) {
            echo $this->ansiFormat('Warning: ', Console::FG_YELLOW);
            echo $message . "\n";
        }
        $this->warning($message);
    }
    public function log($message, $force = false) {
        if($this->verbose || $force) {
            //echo $this->ansiFormat('ISchedule::options($actionID)nfo: ', Console::FG_BLUE);
            $this->info($message);
        }
        $this->info($message);
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
