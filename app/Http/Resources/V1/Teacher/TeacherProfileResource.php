<?php

namespace App\Http\Resources\V1\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'teacher_id'   => $this->teacher_id,
            'name'         => $this->name,
            'phone_number' => $this->phone_number,
            'user'         => [
                'user_id'  => $this->user->user_id,
                'username' => $this->user->username,
                'email'    => $this->user->email,
                'role'     => $this->user->role,
            ],
        ];
    }
}
