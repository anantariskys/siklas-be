<?php

namespace App\Services;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseService
{
    protected BaseRepositoryInterface $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function paginate(int $limit = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($limit);
    }

    public function find(string|int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(string|int $id, array $data): bool|Model
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string|int $id): bool
    {
        return $this->repository->delete($id);
    }
}
