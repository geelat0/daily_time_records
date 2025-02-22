<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TimeEntries extends Model
{

    protected $table = 'time_entries';

    protected $fillable = [
        'user_id',
        'shift_schedule_id',
        'date',
        'time_in',
        'time_out',
        'break_out',
        'break_in',
        'rendered_hours',
        'late',
        'undertime',
        'excess_minutes',
        'status',
    ];

    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class);
    }

    public function approvedAttendance()
    {
        return $this->hasMany(ApprovedAttendance::class);
    }

    public static function getTimeEntries()
    {
        return self::where('user_id', 1)
            ->whereDate('created_at', today())
            ->first();
    }
}
