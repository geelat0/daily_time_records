<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shifts extends Model
{
    use SoftDeletes;

    protected $table = 'shifts';

    protected $fillable = [
        'shift_name',
        'time_in',
        'break_out',
        'break_in',
        'time_out',
        'late_threshold',
        'is_flexi_schedule',
    ];

    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }
    
}
