<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeEntryRequest;
use App\Http\Resources\TimeEntryResouce;
use App\Models\Shifts;
use App\Models\ShiftSchedule;
use App\Models\TimeEntries;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    
    public function index()
    {
        return view('time_entry.time_entry');
    }

    public function updateTimeIn(Request $request)
    {

        $timeEntry = New TimeEntries();
        $timeEntry->user_id = 1;
        $timeEntry->am_time_in = $request->am_time_in;
        $timeEntry->save();

        return response()->json(
            $timeEntry,
            200
        );
    }

    public function updateTimeOut(Request $request)
    {
        $timeEntry = TimeEntries::find($request->id);
        $timeEntry->am_time_out = $request->am_time_out;
        $timeEntry->save();

        return response()->json(
            $timeEntry,
            200
        );
    }

    public function updateTimeInPM(Request $request)
    {
        $userId = 1; 
        $date = now()->format('Y-m-d');
    
        $timeEntry = TimeEntries::firstOrNew(
            ['user_id' => $userId, 'created_at' => $date],
            ['pm_time_in' => $request->pm_time_in]
        );
    
        $timeEntry->pm_time_in = $request->pm_time_in;
        $timeEntry->save();
    

        return response()->json(
            $timeEntry,
            200
        );
        
    }

    public function updateTimeOutPM(Request $request)
    {
        $timeEntry = TimeEntries::find($request->id);
        $timeEntry->pm_time_out = $request->pm_time_out;
        $timeEntry->save();

        return response()->json(
            $timeEntry,
            200
        );
        
    } 

    public function getTimeEntries()
    {
        $timeEntries = TimeEntries::getTimeEntries();

        return response()->json(
            $timeEntries,
            200
        );
    }

    
  
}
