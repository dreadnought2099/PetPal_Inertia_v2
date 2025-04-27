<?php

namespace App\Providers;

use App\Models\Adoption;
use App\Policies\AdoptionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registering custom Blade directive
        Blade::directive('title', function ($expression) {
            return "<?php view()->share('title', $expression); ?>";
        });

        // Register the AdoptionPolicy for Adoption model
        Gate::policy(Adoption::class, AdoptionPolicy::class);

        // You can also define custom gates for actions like approve, reject, delete directly here
        Gate::define('approve-adoption', function ($user, $adoption) {
            return $user->hasRole('Administrator|Shelter');
        });

        Gate::define('reject-adoption', function ($user, $adoption) {
            return $user->hasRole('Administrator|Shelter');
        });

        Gate::define('delete-adoption', function ($user, $adoption) {
            return $user->id === $adoption->user_id && $adoption->status === 'pending';
        });

        Gate::define('change-role', function ($user) {
            return $user->hasPermissionTo('change role');
        });

        Inertia::share([
            'flash' => function() {
                return [
                    'success' => Session::get('success'),
                'error' => Session::get('error'),
                'info' => Session::get('info'),
                ];
            },
            'errors' => function () {
                return session('errors') ? session('errors')->getBag('default')->getMessages() : [];
            },
        ]);
    }
}
