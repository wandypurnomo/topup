<?php


namespace Wandxx\Topup\Repositories;


use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Wandxx\Support\Interfaces\DefaultRequestInterface;
use Wandxx\Topup\Constants\TopUpStatus;
use Wandxx\Topup\Contracts\TopupRepositoryContract;
use Wandxx\Topup\Events\TopupCreated;
use Wandxx\Topup\Events\TopupDeleted;
use Wandxx\Topup\Events\TopupUpdated;
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

    public function userTopup(Request $request, string $userId, int $perPage = 10)
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

    public function makeTopup(DefaultRequestInterface $request, string $userId): Model
    {
        $data = $request->data();
        $data["created_by"] = $userId;

        $model = $this->_model
            ->newQuery()
            ->create($data);

        event(new TopupCreated($model));

        return $model;
    }

    public function updateTopup(DefaultRequestInterface $request, string $code, string $userId): Model
    {
        $data = $request->data();
        $data["created_by"] = $userId;

        $model = $this->_model
            ->newQuery()
            ->where("code", $code)
            ->where("created_by", $userId)
            ->firstOrFail();

        $model->update($data);
        event(new TopupUpdated($model));

        return $model;
    }

    public function deleteTopup(string $code, string $userId): void
    {
        $model = $this->_model
            ->newQuery()
            ->where("code", $code)
            ->where("created_by", $userId)
            ->firstOrFail();

        try {
            event(new TopupDeleted($model));
            $model->delete();
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function markTopupAs(int $status, string $code, string $userId, string $failedMessage = null): Model
    {
        throw_if($status == TopUpStatus::PLACED, new BadRequestHttpException("cant back to placed."));
        throw_if($status == TopUpStatus::FAILED && $failedMessage == null, new BadRequestHttpException("failed message required."));

        $data = ["status" => $status];
        $model = $this->_model
            ->newQuery()
            ->where("code", $code)
            ->where("created_by", $userId)
            ->firstOrFail();

        $model->update($data);

        if ($status == TopUpStatus::FAILED && $failedMessage != null) {
            $model->update(["metadata->failed_message" => $failedMessage]);
        }

        return $model;
    }
}