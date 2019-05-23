<?php

namespace Arrow\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Arrow\User;
use Arrow\Field;

class FieldPolicy
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

    public function update(User $user, Field $field)
    {
        return $user->activeCompany()->id == $field->area->company->id;
    }
}
