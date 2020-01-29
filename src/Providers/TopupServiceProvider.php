<?php


namespace Wandxx\Topup\Providers;


use Carbon\Laravel\ServiceProvider;
use Wandxx\Topup\Contracts\TopupRepositoryContract;
use Wandxx\Topup\Repositories\TopupRepository;

class TopupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->_publishing();
        $this->_bindRepository();
    }

    private function _publishing(): void
    {
        $this->publishes([
            __DIR__ . "/../Migrations" => database_path("migrations")
        ], "migrations");
    }

    private function _bindRepository(): void
    {
        $this->app->bind(TopupRepositoryContract::class, TopupRepository::class);
    }
}