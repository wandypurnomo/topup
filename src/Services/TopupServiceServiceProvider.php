<?php


namespace Wandxx\Topup\Services;


use Illuminate\Support\ServiceProvider;
use Wandxx\Topup\Contracts\TopupRepositoryContract;

class TopupServiceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind("topupService", function () {
            return new TopupService(resolve(TopupRepositoryContract::class));
        });
    }
}