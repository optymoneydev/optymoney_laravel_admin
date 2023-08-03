<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\cron\CronController;

class SchemeMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schmes:Master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Master Schemes update weekly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cron = (new CronController)->schemeMaster();
        $this->info('Successfully sent daily quote to everyone.'.$cron);
    }
}
