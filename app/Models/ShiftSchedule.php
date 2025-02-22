<?php

namespace App\Models;

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
        return $this->belongsTo(Shifts::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntries::class);
    }

    public function approvedAttendance()
    {
        return $this->hasMany(ApprovedAttendance::class);
    }
    

}
