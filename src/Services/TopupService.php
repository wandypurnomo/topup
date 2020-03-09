<?php


namespace Wandxx\Topup\Services;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Wandxx\Topup\Contracts\TopupRepositoryContract;

class TopupService
{
    private $_topupRepository;
    private $_guard;
    public $topup;

    public function __construct(TopupRepositoryContract $_topupRepository)
    {
        $this->_topupRepository = $_topupRepository;
        $this->_guard = "user";
    }

    public function setGuard(string $guard): TopupService
    {
        $this->_guard = $guard;
        return $this;
    }

    public function allUserTopup(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        return $this->_topupRepository->userTopup($request, auth($this->_guard)->id(), $perPage);
    }

    public function userTopupDetailByCode(string $code): UserTopupService
    {
        $this->topup = $this->_topupRepository->findTopupByCode($code, auth($this->_guard)->id());
        return new UserTopupService($this->topup, resolve(TopupRepositoryContract::class), $this->_guard);
    }

    public function userTopupDetailById(string $id): UserTopupService
    {
        $this->topup = $this->_topupRepository->findTopupById($id, Auth::guard($this->_guard)->id());
        return new UserTopupService($this->topup, resolve(TopupRepositoryContract::class), $this->_guard);
    }
}