<?php


namespace Wandxx\Topup\Constants;


use Wandxx\Support\Interfaces\ConstantInterface;
use Wandxx\Support\Traits\HasLabel;

class TopUpStatus implements ConstantInterface
{
    use HasLabel;

    const PLACED = 1;
    const ON_PROGRESS = 2;
    const DONE = 3;
    const FAILED = 4;

    public function labels(): array
    {
        return [
            self::PLACED => "Placed",
            self::ON_PROGRESS => "On Progress",
            self::DONE => "Done",
            self::FAILED => "Failed"
        ];
    }
}