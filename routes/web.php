<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimeSheetController;
use App\DataTables\TimeSheetDataTable;
use App\Http\Controllers\ApprovedAttendanceController;
use App\Http\Controllers\SettingController;


Route::get('/', [TimeEntryController::class, 'index'])->name('time_entry');

//----------------------------------------TIME ENTRY-------------------------------------------------//

Route::get('/get_time_entries', [TimeEntryController::class, 'getTimeEntries'])->name('time_entries');
Route::post('/update/time/in', [TimeEntryController::class, 'updateTimeIn'])->name('update_time_in');
Route::post('/update/time/out', [TimeEntryController::class, 'updateTimeOut'])->name('update_time_out');
Route::post('/update/time/in/pm', [TimeEntryController::class, 'updateTimeInPM'])->name('update_time_in_pm');
Route::post('/update/time/out/pm', [TimeEntryController::class, 'updateTimeOutPM'])->name('update_time_out_pm');
Route::get('/checkShiftSchedule', [TimeEntryController::class, 'checkShiftSchedule'])->name('check_shift_schedule');

//----------------------------------------TIME SHEET-------------------------------------------------//

Route::get('/time_sheet', [TimeSheetController::class, 'index'])->name('time_sheet');
Route::post('/update/time/entry', [TimeSheetController::class, 'updateTimeEntry'])->name('update.time.entry');
Route::get('/getAttendanceType', [TimeSheetController::class, 'getAttendanceType'])->name('get_attendance_tyoe');

//----------------------------------------SETTING-------------------------------------------------//

Route::get('/setting', [SettingController::class, 'index'])->name('setting');

//----------------------------------------APPROVED ATTENDANCE-------------------------------------------------//

Route::post('/store', [ApprovedAttendanceController::class, 'store'])->name('store');



