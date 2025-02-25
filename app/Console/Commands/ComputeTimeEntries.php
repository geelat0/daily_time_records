<?php

namespace App\Console\Commands;

use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimeSheetController;
use Illuminate\Console\Command;

class ComputeTimeEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compute-time-entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new TimeSheetController();
        $controller->computeTimeEntries();
        
        $this->info('Time entries computed successfully.');
    }
}
