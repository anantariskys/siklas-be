<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $limit = 10, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($limit, $columns);
    }

    public function find(string|int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(string|int $id, array $data): bool|Model
    {
        $model = $this->find($id);
        if (!$model) return false;
        
        $model->update($data);
        return $model;
    }

    public function delete(string|int $id): bool
    {
        $model = $this->find($id);
        if (!$model) return false;

        return $model->delete();
    }
}
