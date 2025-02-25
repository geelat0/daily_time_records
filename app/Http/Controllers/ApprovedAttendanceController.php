<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovedAttendanceRequest;
use App\Http\Resources\ApprovedAttendanceResource;
use App\Models\ApprovedAttendance;

class ApprovedAttendanceController extends Controller
{
    /**
     * Store a newly created attendance in storage.
     */
    public function store(ApprovedAttendanceRequest $request)
    {
        // Create a new ApprovedAttendance instance
        $attendance = ApprovedAttendance::create($request->validated());

        // Return the created resource
        return new ApprovedAttendanceResource($attendance);
    }
} 