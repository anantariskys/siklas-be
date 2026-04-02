<?php

namespace App\Repositories;

use App\Models\Dosen;
use App\Repositories\Interfaces\DosenRepositoryInterface;

class DosenRepository extends BaseRepository implements DosenRepositoryInterface
{
    public function __construct(Dosen $model)
    {
        parent::__construct($model);
    }

    public function getFilteredPaginated(array $params, int $limit = 10)
    {
        return $this->model->with(['major:id,nama', 'minors:id,nama'])
            ->when($params['search'] ?? null, function ($query, $search) {
                $query->where('nama', 'LIKE', "%{$search}%");
            })
            ->paginate($limit);
    }
}
