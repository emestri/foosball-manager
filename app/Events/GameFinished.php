<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameFinished
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new game finished event instance.
     *
     * @param  Game  $game
     */
    public function __construct(protected Game $game)
    {
    }
}
