<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_one_id',
        'player_two_id',
    ];

    /**
     * Get the associated user for player one.
     *
     * @return HasOne
     */
    public function playerOne(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'player_one_id');
    }

    /**
     * Get the associated user for player two.
     *
     * @return HasOne
     */
    public function playerTwo(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'player_two_id');
    }

    /**
     * Get the player ids.
     *
     * @return array
     */
    public function players(): array
    {
        return [
            $this->player_one_id,
            $this->player_two_id,
        ];
    }
}
