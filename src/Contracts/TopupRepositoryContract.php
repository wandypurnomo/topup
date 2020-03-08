<?php


namespace Wandxx\Topup\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface TopupRepositoryContract
{
    public function all(Request $request, int $perPage = 10): LengthAwarePaginator;

    public function findTopupByCode(string $topupCode, string $userId): Model;

    public function findTopupById(string $topupId, string $userId): Model;

    public function userTopup(Request $request, string $userId, int $perPage = 10): LengthAwarePaginator;

    public function makeTopup(array $data, string $userId): Model;

    public function updateTopup(array $data, string $code, string $userId): Model;

    public function deleteTopup(string $code, string $userId): void;

    public function markTopupAs(int $status, Model $model, string $failedMessage = null): Model;
}