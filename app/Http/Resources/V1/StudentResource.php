<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id' => $this->student_id,
            'academic_year' => $this->academic_year,
            'level_class' => $this->level_class,
            'religion_name' => $this->religion_name,
            'mentor' => [
                'name_mentor' => $this->mentor->name_mentor ?? "No Mentor Assigned",
                'nip' => $this->mentor->nip ?? "-",
            ],
            'name_student' => $this->name_student,
            'parent_name' => $this->parent->name_parent ?? "No Parent Data",
            'gender' => $this->gender,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
        ];
    }
}
