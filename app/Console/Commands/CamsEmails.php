<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\cron\CronController;

class CamsEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cams:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cams email data fetching';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cron = (new CronController)->camsEmails();
        $this->info('Successfully sent daily quote to everyone.'.$cron);
    }
}
