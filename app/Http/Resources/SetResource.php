<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'game_id' => $this->game_id,
            'home_forwarder_id' => $this->home_forwarder_id,
            'guest_forwarder_id' => $this->guest_forwarder_id,
            'home_forwarder' => UserResource::make($this->whenLoaded('homeForwarder')),
            'guest_forwarder' => UserResource::make($this->whenLoaded('guestForwarder')),
            'home_goals' => $this->home_goals,
            'guest_goals' => $this->guest_goals,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
