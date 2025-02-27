<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovedAttendanceRequest;
use App\Models\ApprovedAttendance;
use Illuminate\Http\Request;

class ApprovedAttendanceController extends Controller
{
    
    /**
     * Store a newly created attendance in storage.
     */
    public function store(ApprovedAttendanceRequest $request)
    {   // Create a new ApprovedAttendance instance
        
        $attendance = new ApprovedAttendance($request->validated());

        $startDates = $request->start_date;
        $endDates = $request->end_date; 

        foreach ($startDates as $index => $startDate) {
            $attendance = new ApprovedAttendance();
            $attendance->user_id = $request->user_id;
            $attendance->start_date = $startDate;
            $attendance->end_date = $endDates[$index];
            $attendance->attendance_type = $request->attendance_type;
            $attendance->file = base64_encode(file_get_contents($request->file));
            $attendance->file_path = $request->file->getPath();
            $attendance->file_name = $request->file->getClientOriginalName();
            $attendance->remarks = $request->remarks;
            $attendance->save();
        }

        return response()->json(
            $attendance,
            200
        );

    }


} 