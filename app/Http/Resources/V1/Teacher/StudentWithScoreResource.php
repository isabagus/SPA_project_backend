<?php

namespace App\Http\Resources\V1\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentWithScoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id'   => $this->student_id,
            'name_student' => $this->name_student,
            'nis'          => $this->nis,
            'gender'       => $this->gender,
            'address'      => $this->address, // Tambahkan alamat
            'level_class'  => $this->level_class, // Tambahkan kelas
            'status_score' => $this->status_score ?? 'none',
            'completion'   => $this->completion ?? 0,
            'report_id'    => $this->report_id ?? null,
            'average_value'=> $this->average_value ?? null,
        ];
    }
}
