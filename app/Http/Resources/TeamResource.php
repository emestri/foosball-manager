<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'player_one_id' => $this->player_one_id,
            'player_two_id' => $this->player_two_id,
            'player_one' => $this->whenLoaded('playerOne', fn($player) => $player->name),
            'player_two' => $this->whenLoaded('playerTwo', fn($player) => $player->name),
            'created_at' => $this->created_at,
        ];
    }
}
