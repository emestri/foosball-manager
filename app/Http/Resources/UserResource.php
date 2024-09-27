<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location_id' => $this->location_id,
            'name' => $this->name,
            'email' => $this->email,
            'location' => LocationResource::make($this->whenLoaded('location')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
