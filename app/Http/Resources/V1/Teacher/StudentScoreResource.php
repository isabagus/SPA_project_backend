<?php

namespace App\Http\Resources\V1\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentWithScoreResource extends JsonResource
{
    /**
     * Transform data siswa + nilai average untuk response teacher.
     *
     * Digunakan pada:
     * - GET /teacher/subjects/{subjectId}/students
     *
     * Field average_value dan has_score di-append oleh TeacherService::getStudentsWithScore
     * menggunakan dynamic property pada model Student.
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id'    => $this->student_id,
            'name_student'  => $this->name_student,
            'level_class'   => $this->level_class,
            'gender'        => $this->gender,
            // Data nilai — null jika belum pernah ada input dari teacher
            'report_id'     => $this->report_id,
            'average_value' => $this->average_value,
            'has_score'     => $this->has_score ?? false,
        ];
    }
}
