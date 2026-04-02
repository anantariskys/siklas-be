<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByUsername(string $username)
    {
        return $this->model->where('username', $username)->first();
    }

    public function getFilteredPaginated(array $params, int $limit = 10)
    {
        return $this->model->whereIn('role', ['mahasiswa', 'kaprodi', 'dosen'])
            ->when($params['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($params['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            })
            ->paginate($limit);
    }
}
