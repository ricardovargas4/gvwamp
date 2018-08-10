<?php

namespace gv\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'gv\Model' => 'gv\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('checkGestor',function($user){
            if($user->nivel<3){
                return true;
            }
            return false;

        });
        Gate::define('checkDev',function($user){
            if($user->nivel<2){
                return true;
            }
            return false;

        });

        //
    }
}
