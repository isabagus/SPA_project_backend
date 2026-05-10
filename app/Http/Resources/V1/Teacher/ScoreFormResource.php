<?php

namespace App\Http\Resources\V1\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Resource ini akan memformat data lengkap form penilaian 1 siswa.
     * Di-generate di TeacherService::getScoreForm.
     */
    public function toArray(Request $request): array
    {
        // $this merujuk ke array asosiatif yang dikirim dari service, 
        // bukan instance Model tunggal.
        return [
            'student' => [
                'student_id'   => $this['student']->student_id,
                'name_student' => $this['student']->name_student,
                'level_class'  => $this['student']->level_class,
            ],
            'subject' => [
                'subject_id'       => $this['subject']->subject_id,
                'category_subject' => $this['subject']->category_subject,
                'term'             => $this['subject']->term,
            ],
            'report_id'     => $this['report_id'],
            'average_value' => $this['average_value'],
            'rubrics'       => $this['rubrics'], 
        ];
    }
}
