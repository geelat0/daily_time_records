<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'shift_schedules';

    protected $fillable = [
        'user_id',
        'shift_id',
    ];

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'id');
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntries::class);
    }

    public function approvedAttendance()
    {
        return $this->hasMany(ApprovedAttendance::class);
    }

    public static function hasTodayShiftSchedule($userId)
    {
        $today = Carbon::today()->toDateString();

        return self::where('user_id', $userId)
            ->whereJsonContains('dates', $today)
            ->first();
    }
    

}
