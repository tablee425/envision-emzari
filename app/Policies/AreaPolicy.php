<?php

namespace Arrow\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Arrow\User;
use Arrow\Area;

class AreaPolicy
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

    public function view(User $user, Area $area)
    {
        return Area::whereIn('company_id', $user->companies()->pluck('companies.id'))->get()->contains($area);
    }


}
