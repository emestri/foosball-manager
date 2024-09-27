<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'location_id' => $this->location_id,
            'location'  => LocationResource::make($this->whenLoaded('location')),
            'home_team_id'  => $this->home_team_id,
            'home_team'  => TeamResource::make($this->whenLoaded('homeTeam')),
            'guest_team_id'  => $this->guest_team_id,
            'guest_team'  => TeamResource::make($this->whenLoaded('guestTeam')),
            'sets' => SetResource::collection($this->whenLoaded('sets')),
            'mode'  => $this->mode,
            'winner'  => $this->winner,
            'kickoff_at'  => $this->kickoff_at,
            'finished_at'  => $this->finished_at,
        ];
    }
}
