<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovedAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'attendance_type' => 'required|string',
            'file' => 'required|mimes:pdf,jpeg,png,jpg,gif',
            'file_path' => 'nullable',
            'file_name' => 'nullable',
            'remarks' => 'required|string',
            'dates' => 'nullable',
        ];
    }
}
