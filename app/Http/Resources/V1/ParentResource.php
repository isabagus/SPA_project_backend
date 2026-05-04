<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parent_id' => $this->parent_id,
            'name_parent' => $this->name_parent,
            'student' => new StudentResource($this->whenLoaded('student')),
            'user' => [
                'user_id' => $this->user->user_id,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'role' => $this->user->role,
            ],
        ];
    }
}
