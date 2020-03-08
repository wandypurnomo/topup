<?php


namespace Wandxx\Topup\Services;


use Illuminate\Support\Facades\Facade;

class TopupServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "topupService";
    }
}