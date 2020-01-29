<?php


namespace Wandxx\Topup\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopupDone
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}