<?php

namespace App\Repositories\Interfaces;

interface DosenRepositoryInterface extends BaseRepositoryInterface
{
    public function getFilteredPaginated(array $params, int $limit = 10);
}
