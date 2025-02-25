<?php

namespace App\Http\Controllers;

use App\DataTables\TimeSheetDataTable;
use App\Http\Requests\TimeEntryRequest;
use App\Http\Resources\TimeEntryResouce;
use App\Models\Shifts;
use App\Models\ShiftSchedule;
use App\Models\TimeEntries;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB ;

class TimeSheetController extends Controller
{
    public function index(TimeSheetDataTable $dataTable)
    {
        return $dataTable->render('time_sheet.time_sheet');
    }

    public function computeTimes($timeEntry)
    {
        $shift = null;

        if ($timeEntry->shift_schedule_id) {
            $shiftSchedule = ShiftSchedule::find($timeEntry->shift_schedule_id);
            $shift = Shifts::find($shiftSchedule->shift_id);
        }

        if (!$shift) {
            $dayOfWeek = now()->dayOfWeek;
            if ($dayOfWeek == Carbon::MONDAY) {
                $shift = (object) [
                    'am_time_in' => '07:00:00',
                    'am_time_out' => '12:00:00',
                    'pm_time_in' => '13:00:00',
                    'pm_time_out' => '17:00:00',
                    'am_late_threshold' => '08:00:00',
                    'pm_late_threshold' => '13:00:00',
                    'is_flexi_schedule' => true,
                ];
            } else {
                $shift = (object) [
                    'am_time_in' => '07:00:00',
                    'am_time_out' => '12:00:00',
                    'pm_time_in' => '13:00:00',
                    'pm_time_out' => '17:30:00',
                    'am_late_threshold' => '08:30:00',
                    'pm_late_threshold' => '13:00:00',
                    'is_flexi_schedule' => true,
                ];
            }
        }


        if ($shift) {
            $amTimeIn = $timeEntry->am_time_in ? Carbon::parse($timeEntry->am_time_in) : null;
            $amTimeOut = $timeEntry->am_time_out ? Carbon::parse($timeEntry->am_time_out) : null;
            $pmTimeIn = $timeEntry->pm_time_in ? Carbon::parse($timeEntry->pm_time_in) : null;
            $pmTimeOut = $timeEntry->pm_time_out ? Carbon::parse($timeEntry->pm_time_out) : null;

            $shiftAmTimeIn = $shift->am_time_in ? Carbon::parse($shift->am_time_in) : null;
            $shiftAmTimeOut = $shift->am_time_out ? Carbon::parse($shift->am_time_out) : null;
            $shiftPmTimeIn = $shift->pm_time_in ? Carbon::parse($shift->pm_time_in) : null;
            $shiftPmTimeOut = $shift->pm_time_out ? Carbon::parse($shift->pm_time_out) : null;
            $AMlateThreshold = $shift->am_late_threshold ? Carbon::parse($shift->am_late_threshold) : null;
            $PMlateThreshold = $shift->pm_late_threshold ? Carbon::parse($shift->pm_late_threshold) : null;


            // Calculate late hours using late_threshold
            if ($amTimeIn && $AMlateThreshold && $amTimeIn->greaterThan($AMlateThreshold)) {
                $AMLateHours = $AMlateThreshold->diffInMinutes($amTimeIn);
            } else {
                $AMLateHours = 0;
            }

            if ($pmTimeIn && $PMlateThreshold && $pmTimeIn->greaterThan($PMlateThreshold)) {
                $PMLateHours = $PMlateThreshold->diffInMinutes($pmTimeIn);
            
            } else {
                $PMLateHours = 0;
            }

            $totalLateMinutes = $AMLateHours + $PMLateHours;
            $lateHours = intdiv($totalLateMinutes, 60);
            $lateMinutes = $totalLateMinutes % 60;
            $timeEntry->late_hours = sprintf('%02d:%02d', $lateHours, $lateMinutes);

            // Calculate rendered hours
            $AMrenderedHours = 0;
            $PMrenderedHours = 0;
            if ($amTimeIn && $amTimeOut) {
                $AMrenderedHours += $amTimeIn->diffInMinutes($amTimeOut);
               
            }
            if ($pmTimeIn && $pmTimeOut) {
                $PMrenderedHours += $pmTimeIn->diffInMinutes($pmTimeOut);

            }

            $renderedHours = $AMrenderedHours + $PMrenderedHours;
            $hours = intdiv($renderedHours, 60);
            $minutes = $renderedHours % 60;
            $timeEntry->rendered_hours = sprintf('%02d:%02d', $hours, $minutes);

            // Calculate excess minutes
            $AMscheduledHours = 0;
            $PMscheduledHours = 0;
            if ($shiftAmTimeIn && $shiftAmTimeOut) {
                $AMscheduledHours += $shiftAmTimeIn->diffInMinutes($shiftAmTimeOut);
            }
            if ($shiftPmTimeIn && $shiftPmTimeOut) {
                $PMscheduledHours += $shiftPmTimeIn->diffInMinutes($shiftPmTimeOut);
            }

            $AMexcessMinutes = 0;
            $PMexcessMinutes = 0;
            if ($amTimeOut && $shiftAmTimeOut && $amTimeOut->greaterThan($shiftAmTimeOut)) {
                $AMexcessMinutes = $amTimeOut->diffInMinutes($shiftAmTimeOut);
            }
            if ($pmTimeOut && $shiftPmTimeOut && $pmTimeOut->greaterThan($shiftPmTimeOut)) {
                $PMexcessMinutes = $pmTimeOut->diffInMinutes($shiftPmTimeOut);

            }

            $excessMinutes = $AMexcessMinutes + $PMexcessMinutes;
        
            // Convert excess minutes to time format
            $hours = intdiv($excessMinutes, 60);
            $minutes = $excessMinutes % 60;
            $timeEntry->excess_minutes = sprintf('%02d:%02d', $hours, $minutes);

            $timeEntry->save();
        }
    }

    public function computeTimeEntries()
    {
        $timeEntries = TimeEntries::where('user_id', 1)
            ->whereDate('created_at', today())
            ->get();

        foreach ($timeEntries as $timeEntry) {
            $this->computeTimes($timeEntry);
        }

        return response()->json(
            $timeEntries,
            200
        );
    }

    public function updateTimeEntry(TimeEntryRequest $request)
    {
        $timeEntry = TimeEntries::findOrFail($request->id);

        if ($request->has('am_time_in')) {
            $timeEntry->am_time_in = $request->am_time_in;
        }
        if ($request->has('am_time_out')) {
            $timeEntry->am_time_out = $request->am_time_out;
        }
        if ($request->has('pm_time_in')) {
            $timeEntry->pm_time_in = $request->pm_time_in;
        }
        if ($request->has('pm_time_out')) {
            $timeEntry->pm_time_out = $request->pm_time_out;
        }

        $timeEntry->save();
        $this->computeTimes($timeEntry);

        return new TimeEntryResouce($timeEntry);
    }

    
}
