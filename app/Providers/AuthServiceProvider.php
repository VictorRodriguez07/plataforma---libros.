<?php

namespace App\Providers;

use App\User;
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
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPrestamosLibros();
    }

   
        

    public function registerPrestamosLibros()
    {
        Gate::define('marcar-prestamo-entregado', function($user)
        {
            return $user->hasAccess(['marcar-prestamo-entregado']);
        });
        Gate::define('finalizar-prestamo', function($user)
        {
            return $user->hasAccess(['finalizar-prestamo']);
        });
        Gate::define('aprobar-solicitud-prestamo', function($user)
        {
            return $user->hasAccess(['aprobar-solicitud-prestamo']);
        });
        Gate::define('denegar-solicitud-prestamo', function($user)
        {
            return $user->hasAccess(['denegar-solicitud-prestamo']);
        });
        Gate::define('aprobar-solicitud-aumento-plazo', function($user)
        {
            return $user->hasAccess(['aprobar-solicitud-aumento-plazo']);
        });
        Gate::define('denegar-solicitud-aumento-plazo', function($user)
        {
            return $user->hasAccess(['denegar-solicitud-aumento-plazo']);
        });
        
    }
}
