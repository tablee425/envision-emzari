<?php

namespace Arrow\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Arrow\User;
use Arrow\Location;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Location $location)
    {
        return !$user->activeCompany()->locations($location->name)->isEmpty();
    }
}
