<?php

namespace Arrow\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Arrow\Location' => 'Arrow\Policies\LocationPolicy',
        'Arrow\Area' => 'Arrow\Policies\AreaPolicy',
        'Arrow\Field' => 'Arrow\Policies\FieldPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // Gate::define('get-locations', function ($user, $company) {
        //     return $user->activeCompany()->id == $company->id;
        // });
        //
    }
}
