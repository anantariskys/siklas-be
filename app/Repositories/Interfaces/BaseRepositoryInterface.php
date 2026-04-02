<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $limit = 10, array $columns = ['*']): LengthAwarePaginator;
    public function find(string|int $id): ?Model;
    public function create(array $data): Model;
    public function update(string|int $id, array $data): bool|Model;
    public function delete(string|int $id): bool;
}
