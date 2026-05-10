<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SubmitScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'teacher';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_year'                => 'required|string|exists:academic_years,academic_year',
            'scores'                       => 'required|array|min:1',
            'scores.*.rubric_id'           => 'required|integer|exists:rubric_categories,rubric_id',
            'scores.*.score'               => 'required|numeric|min:1|max:3',
            'scores.*.description_subject' => 'nullable|string|max:1000',
        ];
    }
}
