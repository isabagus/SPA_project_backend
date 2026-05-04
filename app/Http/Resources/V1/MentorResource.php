<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MentorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'mentor_id' => $this->mentor_id,
            'name_mentor' => $this->name_mentor,
            'nip' => $this->nip,
            'phone_number' => $this->phone_number,
            'user' => [
                'user_id' => $this->user->user_id,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'role' => $this->user->role,
            ],
        ];
    }
}
