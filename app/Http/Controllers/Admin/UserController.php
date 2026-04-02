<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    use ApiResponse;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[OA\Get(
        path: '/admin/user',
        operationId: 'listUsers',
        summary: 'List and search users',
        description: 'Retrieve a paginated list of users with search and role filters',
        tags: ['Admin - Users'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'search', in: 'query', description: 'Search by name or email', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'role', in: 'query', description: 'Filter by role', schema: new OA\Schema(type: 'string', enum: ['mahasiswa', 'kaprodi', 'dosen']))]
    #[OA\Parameter(name: 'limit', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Users retrieved successfully'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                new OA\Property(property: 'meta', type: 'object'),
            ]
        )
    )]
    public function index(Request $request)
    {
        $users = $this->userService->listUsers($request->all(), $request->query('limit', 10));

        return $this->successPaginationResponse(
            $users->items(),
            [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
            'Users retrieved successfully',
        );
    }

    #[OA\Post(
        path: '/admin/user',
        operationId: 'storeUser',
        summary: 'Create a new user',
        tags: ['Admin - Users'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreUserRequest'))]
    #[OA\Response(response: 201, description: 'User created successfully')]
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $user = $this->userService->create($data);
            return $this->successResponse($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'User creation failed');
        }
    }

    #[OA\Put(
        path: '/admin/user/{id}',
        operationId: 'updateUser',
        summary: 'Update an existing user',
        tags: ['Admin - Users'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserRequest'))]
    #[OA\Response(response: 200, description: 'User updated successfully')]
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $user = $this->userService->update($id, $data);
            
            if (!$user) {
                return $this->errorResponse('User not found', 'User update failed', 404);
            }
            
            return $this->successResponse($user, 'User updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'User update failed');
        }
    }

    #[OA\Delete(
        path: '/admin/user/{id}',
        operationId: 'deleteUser',
        summary: 'Delete a user',
        tags: ['Admin - Users'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'User deleted successfully')]
    public function destroy(Request $request, $id)
    {
        try {
            $this->userService->delete($id);
            return $this->successResponse(null, 'User berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal menghapus user');
        }
    }
}
