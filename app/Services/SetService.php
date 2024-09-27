<?php

namespace App\Services;

use App\Models\Set;
use App\Models\User;
use LogicException;

class SetService
{
    public function create(
        User $homeForwarder,
        User $guestForwarder,
        int $homeGoals,
        int $guestGoals
    ): Set {

    }
}
