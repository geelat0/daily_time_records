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
        $shiftIds = DB::table('shifts')->pluck('id');

        foreach ($shiftIds as $shiftId) {
            DB::table('shift_schedules')->insert([
                'dates' => json_encode(['2025-02-20', '2025-02-21', '2025-02-22']),
                'shift_id' => $shiftId,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
