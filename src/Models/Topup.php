<?php


namespace Wandxx\Topup\Models;


use Envant\Fireable\FireableAttributes;
use Illuminate\Database\Eloquent\Model;
use Wandxx\Support\Traits\UuidForKey;
use Wandxx\Topup\Constants\TopUpStatus;
use Wandxx\Topup\Events\TopupDone;
use Wandxx\Topup\Events\TopupFailed;
use Wandxx\Topup\Events\TopupOnProgress;

class Topup extends Model
{
    use UuidForKey, FireableAttributes;

    protected $guarded = ["id"];
    protected $casts = ["metadata" => "array"];
    protected $fireableAttributes = [
        "status" => [
            TopUpStatus::ON_PROGRESS => TopupOnProgress::class,
            TopUpStatus::DONE => TopupDone::class,
            TopUpStatus::FAILED => TopupFailed::class,
        ]
    ];
}