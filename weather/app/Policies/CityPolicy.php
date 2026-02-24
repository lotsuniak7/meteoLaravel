<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;

class CityPolicy
{
    public function update(User $user, City $city): bool
    {
        return $user->id === $city->user_id;
    }

    public function delete(User $user, City $city): bool
    {
        return $user->id === $city->user_id;
    }
}
