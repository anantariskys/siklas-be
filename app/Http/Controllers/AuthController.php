<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: '/auth/login',
        operationId: 'authenticate',
        summary: 'User Authentication',
        description: 'Authenticate user and return token',
        tags: ['Auth'],
        security: []
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['username', 'password'],
            properties: [
                new OA\Property(property: 'username', type: 'string', example: 'admin'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Login berhasil'),
                new OA\Property(property: 'data', type: 'object', properties: [
                    new OA\Property(property: 'user', type: 'object'),
                    new OA\Property(property: 'token', type: 'string'),
                ]),
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 422, description: 'Validation Error')]
    public function authenticate(LoginRequest $request)
    {
        try {
            $data = $this->authService->authenticate($request->validated());
            return $this->successResponse($data, 'Login berhasil');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Login gagal', 401);
        }
    }
}
