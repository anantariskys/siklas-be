<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email);
    public function findByUsername(string $username);
    public function getFilteredPaginated(array $params, int $limit = 10);
}
