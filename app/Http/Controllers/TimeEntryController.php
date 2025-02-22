<?php

namespace App\Http\Controllers;

use App\Models\TimeEntries;
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
        $timeEntry = TimeEntries::find($request->id);
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
