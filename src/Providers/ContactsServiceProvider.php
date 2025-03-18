<?php

namespace Vitaliiriabyshenko\Contacts\Providers;

use Illuminate\Routing\Route;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class ContactsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Vitaliiriabyshenko\Contacts\Repositories\ContactRepositoryInterface::class,
            \Vitaliiriabyshenko\Contacts\Repositories\ContactRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/contacts.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'contacts');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ]);

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/contacts'),
        ]);

        Paginator::useBootstrap();
    }

    protected function loadRoutes(): void
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/contacts.php');
    }
}
