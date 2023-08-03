<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\cron\CronController;

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amfi:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating the AMFI data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cron = (new CronController)->amfiData();
        $this->info('Successfully sent daily quote to everyone.'.$cron);
    }
}
