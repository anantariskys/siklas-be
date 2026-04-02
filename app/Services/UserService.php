<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * @property UserRepositoryInterface $repository
 */
class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
    
    public function listUsers(array $params, int $limit = 10)
    {
        return $this->repository->getFilteredPaginated($params, $limit);
    }
}
