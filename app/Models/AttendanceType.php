<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    protected $table = 'attendance_type';

    public static function getAttendanceType()
    {
        $attendanceType = AttendanceType::all();

        return response()->json(
            $attendanceType,
            200
        );
    }
}
