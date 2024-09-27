<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Set extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_forwarder_id',
        'guest_forwarder_id',
        'home_goals',
        'guest_goals',
    ];

    /**
     * Get the home forwarder associated with the user.
     *
     * @return HasOne
     */
    public function homeForwarder(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'home_forwarder_id');
    }

    /**
     * Get the home forwarder associated with the user.
     *
     * @return HasOne
     */
    public function guestForwarder(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'guest_forwarder_id');
    }

    /**
     * Get the game of the set.
     *
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
