<?php

namespace App\Repositories\Interfaces;

interface RiwayatKlasifikasiRepositoryInterface extends BaseRepositoryInterface
{
    public function getDashboardStats(\App\Models\User $user): array;
    public function getFilteredPaginated(\App\Models\User $user, array $params, int $limit = 10);
}
