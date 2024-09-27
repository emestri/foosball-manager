<?php

namespace App\Models;

use App\Game\GameMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_id',
        'home_team_id',
        'guest_team_id',
        'mode',
        'winner',
        'kickoff_at',
        'finished_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'kickoff_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
        'mode' => GameMode::class,
    ];


    /**
     * Get the home team.
     *
     * @return HasOne
     */
    public function homeTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'home_team_id');
    }

    /**
     * Get the guest team.
     *
     * @return HasOne
     */
    public function guestTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'guest_team_id');
    }

    /**
     * Get the sets of the game.
     *
     * @return HasMany
     */
    public function sets(): HasMany
    {
        return $this->hasMany(Set::class, 'game_id', 'id');
    }

    /**
     * Get the location associated with the game.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
