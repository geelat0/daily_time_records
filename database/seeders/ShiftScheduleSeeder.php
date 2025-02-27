<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shiftId = DB::table('shifts')->first()->id;

        // foreach ($shiftIds as $shiftId) {
            DB::table('shift_schedules')->insert([
                'start_date' => now(),
                'end_date' => now(),
                'shift_id' => $shiftId,
                'user_id' => 1,
                'remarks' => '',
                'status' => 'For Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        // }
    }
}
