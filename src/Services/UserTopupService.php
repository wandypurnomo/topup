<?php


namespace Wandxx\Topup\Services;


use Illuminate\Database\Eloquent\Model;
use Wandxx\Topup\Contracts\TopupRepositoryContract;

class UserTopupService
{
    private $topup;
    private $_topupRepository;
    private $_guard;

    public function __construct(Model $topup,TopupRepositoryContract $_topupRepository,string $_guard = "user")
    {
        $this->topup = $topup;
        $this->_topupRepository = $_topupRepository;
        $this->_guard = $_guard;
    }

    public function makeTopup(array $data): Model
    {
        return $this->_topupRepository->makeTopup($data, auth($this->_guard)->id());
    }

    public function updateTopup(array $data): Model
    {
        return $this->_topupRepository->updateTopup($data, $this->topup->code, $this->topup->created_by);
    }

    public function deleteTopup(): void
    {
        $this->_topupRepository->deleteTopup($this->topup->code, $this->topup->created_by);
    }

    public function markTopupAs(int $status, ?string $failedMessage = null): Model
    {
        return $this->_topupRepository->markTopupAs($status, $this->topup, $failedMessage);
    }
}