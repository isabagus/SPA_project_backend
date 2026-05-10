<?php

namespace App\Http\Resources\V1\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subject_id' => $this->subject_id,
            'category_subject' => $this->category_subject,
            'term' => $this->term,
            'level_class' => $this->level_class,
            'rubrics' => $this->whenLoaded('rubrics', function () {
                return $this->rubrics->map(function ($rubric) {
                    return [
                        'rubric_id' => $rubric->rubric_id,
                        'rubric_name' => $rubric->rubric_name,
                    ];
                });
            }),
        ];
    }
}
