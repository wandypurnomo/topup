<?php


namespace Wandxx\Topup\Repositories;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Wandxx\Topup\Constants\TopUpStatus;
use Wandxx\Topup\Contracts\TopupRepositoryContract;
use Wandxx\Topup\Models\Topup;

class TopupRepository implements TopupRepositoryContract
{
    private $_model;

    public function __construct(Topup $_model)
    {
        $this->_model = $_model;
    }

    public function all(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->_model->newQuery();

        $where = function (Builder $q) use ($request) {
            if ($request->has("code") && $request->get("code") != "") {
                $q->where("code", $request->get("code"));
            }
        };

        $query->where($where);
        return $query->paginate($perPage);
    }

    public function userTopup(Request $request, string $userId, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->_model->newQuery();
        $where = function (Builder $q) use ($request, $userId) {
            $q->where("created_by", $userId);

            if ($request->has("code") && $request->get("code") != "") {
                $q->where("code", $request->get("code"));
            }
        };
        $query->where($where);
        return $query->paginate($perPage);
    }

    public function makeTopup(array $data, string $userId): Model
    {
        $data["created_by"] = $userId;
        return $this->_model
            ->newQuery()
            ->create($data);
    }

    public function updateTopup(array $data, string $code, string $userId): Model
    {
        ;
        $data["created_by"] = $userId;
        $model = $this->_model
            ->newQuery()
            ->where("code", $code)
            ->where("created_by", $userId)
            ->firstOrFail();

        $model->update($data);
        return $model;
    }

    public function deleteTopup(string $code, string $userId): void
    {
        $model = $this->_model
            ->newQuery()
            ->where("code", $code)
            ->where("created_by", $userId)
            ->firstOrFail();

        $model->delete();
    }

    public function markTopupAs(int $status, Model $topup, string $failedMessage = null): Model
    {
        throw_if($status == TopUpStatus::PLACED, new BadRequestHttpException("cant back to placed."));
        throw_if($status == TopUpStatus::FAILED && $failedMessage == null, new BadRequestHttpException("failed message required."));

        $data = ["status" => $status];
        $model = $topup;

        $model->update($data);

        if ($status == TopUpStatus::FAILED && $failedMessage != null) {
            $model->update(["metadata->failed_message" => $failedMessage]);
        }

        return $model;
    }

    public function findTopupByCode(string $topupCode, string $userId): Model
    {
        return $this
            ->_model
            ->newQuery()
            ->where("code", $topupCode)
            ->where("created_by", $userId)
            ->firstOrFail();
    }

    public function findTopupById(string $topupId, string $userId): Model
    {
        return $this
            ->_model
            ->newQuery()
            ->where("id", $topupId)
            ->where("created_by", $userId)
            ->firstOrFail();
    }
}