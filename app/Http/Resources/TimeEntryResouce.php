<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TimeEntryResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            // 'user_id' => Auth::user()->id,
            // 'time_in' => $this->am_time_in,
            // 'break_out' => $this->am_time_out,
            // 'break_in' => $this->pm_time_in,
            // 'time_out' => $this->pm_time_out,
        ];
    }
}
