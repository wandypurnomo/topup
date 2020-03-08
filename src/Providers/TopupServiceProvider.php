<?php


namespace Wandxx\Topup\Providers;


use Carbon\Laravel\ServiceProvider;
use Wandxx\Topup\Contracts\TopupRepositoryContract;
use Wandxx\Topup\Repositories\TopupRepository;
use Wandxx\Topup\Services\TopupService;

class TopupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->_publishing();
        $this->_bindRepository();
        $this->_registerServices();
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

    private function _registerServices(): void
    {
        $this->app->bind("topupService", function () {
            return new TopupService(resolve(TopupRepositoryContract::class));
        });
    }
}