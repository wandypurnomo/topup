<?php


namespace Wandxx\Topup\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Wandxx\Support\Interfaces\DefaultRequestInterface;

interface TopupRepositoryContract
{
    public function all(Request $request, int $perPage = 10): LengthAwarePaginator;

    public function userTopup(Request $request, string $userId, int $perPage = 10);

    public function makeTopup(array $data, string $userId): Model;

    public function updateTopup(array $data, string $code, string $userId): Model;

    public function deleteTopup(string $code, string $userId): void;

    public function markTopupAs(int $status, string $code, string $userId, string $failedMessage = null): Model;
}