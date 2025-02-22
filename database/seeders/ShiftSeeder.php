<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Define shift modes
                $shiftModes = [
                    [
                        'shift_name' => 'Standard Day Shift 8am to 5pm',
                        'am_time_in' => '08:00:00',
                        'am_time_out' => '17:00:00',
                        'pm_time_in' => '13:00:00',
                        'pm_time_out' => '22:00:00',
                        'late_threshold' => '08:00:00',
                        'is_flexi_schedule' => false,
                    ],
                    [
                        'shift_name' => 'Standard Day Shift 7am to 4pm',
                        'am_time_in' => '07:00:00',
                        'am_time_out' => '16:00:00',
                        'pm_time_in' => '13:00:00',
                        'pm_time_out' => '22:00:00',
                        'late_threshold' => '08:00:00',
                        'is_flexi_schedule' => false,
                    ],
                    [
                        'shift_name' => 'Night Shift 11pm to 6am',
                        'am_time_in' => '22:00:00',
                        'am_time_out' => '06:00:00',
                        'pm_time_in' => '02:00:00',
                        'pm_time_out' => '03:00:00',
                        'late_threshold' => '22:00:00',
                        'is_flexi_schedule' => false,
                    ],
                    [
                        'shift_name' => 'Flexible Day Shift 7am to 5:30pm',
                        'am_time_in' => '07:00:00',
                        'am_time_out' => '17:30:00',
                        'pm_time_in' => '13:00:00',
                        'pm_time_out' => '22:00:00',
                        'late_threshold' => '08:00:00',
                        'is_flexi_schedule' => true,
                    ],
                ];
        
                // Insert shift modes and keep track of their IDs
                $shiftModeIds = [];
                foreach ($shiftModes as $shiftMode) {
                    $exists = DB::table('shifts')->where('shift_name', $shiftMode['shift_name'])->exists();
        
                    if (! $exists) {
                        $id = DB::table('shifts')->insertGetId($shiftMode);
                        $shiftModeIds[$shiftMode['shift_name']] = $id;
                    } else {
                        $shiftModeIds[$shiftMode['shift_name']] = DB::table('shifts')->where('shift_name', $shiftMode['shift_name'])->value('id');
                    }
                }
    }
}
