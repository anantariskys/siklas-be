<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(array $credentials)
    {
        $user = $this->userRepository->findByUsername($credentials['username']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
