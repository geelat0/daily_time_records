<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shift_schedule_id')->nullable();
            $table->unsignedBigInteger('approved_attendance')->nullable();
            $table->unsignedBigInteger('attendance_type_id')->nullable();
            $table->time('am_time_in')->nullable();
            $table->time('am_time_out')->nullable();
            $table->time('pm_time_in')->nullable();
            $table->time('pm_time_out')->nullable();
            $table->time('rendered_hours')->nullable();
            $table->time('excess_minutes')->nullable();
            $table->time('late_hours')->nullable();
            $table->time('undertime_minutes')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
