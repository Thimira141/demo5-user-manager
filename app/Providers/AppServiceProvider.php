<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();

        /**
         * gates
         */
        Gate::define("CREATE_USER", fn (User $user) => $user->permissions()->contains('name', 'CREATE_USER'));
        Gate::define("DELETE_USER", fn (User $user) => $user->permissions()->contains('name', 'DELETE_USER'));
        Gate::define("UPDATE_USER", fn (User $user) => $user->permissions()->contains('name', 'UPDATE_USER'));
        Gate::define("EXPORT_USER_DATA", fn (User $user) => $user->permissions()->contains('name', 'EXPORT_USER_DATA'));
        Gate::define("CREATE_ROLE", fn (User $user) => $user->permissions()->contains('name', 'CREATE_ROLE'));
        Gate::define("DELETE_ROLE", fn (User $user) => $user->permissions()->contains('name', 'DELETE_ROLE'));
        Gate::define("UPDATE_ROLE", fn (User $user) => $user->permissions()->contains('name', 'UPDATE_ROLE'));
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
