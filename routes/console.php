<?php

use App\Http\Controllers\TimeSheetController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:compute-time-entries', function () {
    $controller = new TimeSheetController();
    $controller->computeTimeEntries();
    $this->comment('Time entries computed successfully.');
})->purpose('Compute time entries');