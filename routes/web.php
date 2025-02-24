<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimeSheetController;
use App\DataTables\TimeSheetDataTable;

// Route::get('/', function () {
//     return view('components.app');
// });

Route::get('/', [TimeEntryController::class, 'index'])->name('time_entry');
Route::get('/time_sheet', [TimeSheetController::class, 'index'])->name('time_sheet');

Route::post('/update_time_in', [TimeEntryController::class, 'updateTimeIn'])->name('update_time_in');
Route::post('/update_time_out', [TimeEntryController::class, 'updateTimeOut'])->name('update_time_out');
Route::post('/update_time_in_pm', [TimeEntryController::class, 'updateTimeInPM'])->name('update_time_in_pm');
Route::post('/update_time_out_pm', [TimeEntryController::class, 'updateTimeOutPM'])->name('update_time_out_pm');

Route::get('/time_entries', [TimeEntryController::class, 'getTimeEntries'])->name('time_entries');


