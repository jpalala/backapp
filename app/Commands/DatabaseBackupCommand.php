<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'command:backup {dbname?} {dbuser?} {dbpass?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Database backup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $today = today()->format('Y-m-d');
      if(!is_dir(storage_path('backups'))) {
        mkdir(storage_path('backups'));
      }
      
      // setup defaults from the config 
      $default_dbname = config('database.connections.mysql.database');
      $default_dbuser = config('database.connections.mysql.username');
      $default_dbpass = config('database.connections.mysql.password');

      // so that options override the config from env. 
      $dbname = $this->option('dbname') ?? $default_dbname;
      $dbuser = $this->option('dbuser') ?? $default_dbuser;
      $dbpass = $this->option('dbpass') ?? $default_dbpass;

      // If anything is empty, it's over
      if(empty($dbname)) {
        Log::error('Database name not specified');
	$this->error('Empty DB name');
	return;
      }

      if(empty($dbuser)) {
        Log::error('Database username not specified');
	$this->error('Empty DB username');
	return;
      }

      if(empty($dbpass)) {
        Log::error('Database password not specified');
	$this->error('Empty DB password');
	return;
      }
     
      // create a process
      $this->process = new Process(sprintf(
          'mysqldump --compact --skip-comments -u%s -p%s  %s > %s',   
	  $dbuser,
	  $dbpass,
	  $dbname,
	  storage_path("backups/{$today}.sql")
  ));

      // actually try to run the process
      try {
        $this->process->mustRun();
	Log::info('Daily DB backup - is successful!');
      } catch(ProcessFailedException $exception) {
        Log.error('Daily DB backup- has failed', $exception);
      }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->dailyAt('08:00');
    }
}
