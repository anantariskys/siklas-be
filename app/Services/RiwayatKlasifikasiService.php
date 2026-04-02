<?php

namespace App\Services;

use App\Repositories\Interfaces\RiwayatKlasifikasiRepositoryInterface;

/**
 * @property RiwayatKlasifikasiRepositoryInterface $repository
 */
class RiwayatKlasifikasiService extends BaseService
{
    public function __construct(RiwayatKlasifikasiRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getDashboardStats(\App\Models\User $user)
    {
        return $this->repository->getDashboardStats($user);
    }

    public function listRiwayat(\App\Models\User $user, array $params, int $limit = 10)
    {
        return $this->repository->getFilteredPaginated($user, $params, $limit);
    }
}
