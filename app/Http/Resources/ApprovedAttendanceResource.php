<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovedAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'user_id' => $this->user_id,
            'dates' => $this->dates,
            'attendance_type' => $this->attendance_type,
            'file' =>  base64_encode(file_get_contents($this->file)),
            'file_path' => $this->file->getPath(),
            'file_name' => $this->file->getClientOriginalName(),
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
