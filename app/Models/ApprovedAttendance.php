<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovedAttendance extends Model
{
    use SoftDeletes;

    protected $table = 'approved_attendance';

    protected $fillable = [
        'user_id',
        'dates',
        'attendance_type',
        'file',
        'file_path',
        'file_name',
        'remarks',
    ];

    public function timeEntry()
    {
        return $this->belongsTo(TimeEntries::class);
        
        
    }
    
}
