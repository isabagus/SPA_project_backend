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
            'mentor_id' => $this->mentor_id,
            'name_student' => $this->name_student,
            'gender' => $this->gender,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
        ];
    }
}
