<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dosen\StoreDosenRequest;
use App\Http\Requests\Admin\Dosen\UpdateDosenRequest;
use App\Services\DosenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DosenController extends Controller
{
    use ApiResponse;

    protected DosenService $dosenService;

    public function __construct(DosenService $dosenService)
    {
        $this->dosenService = $dosenService;
    }

    #[OA\Get(
        path: '/admin/dosen',
        operationId: 'listDosens',
        summary: 'List and search lecturers',
        tags: ['Admin - Dosens'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'search', in: 'query', description: 'Search by name', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'limit', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'List dosen berhasil diambil'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                new OA\Property(property: 'meta', type: 'object'),
            ]
        )
    )]
    public function index(Request $request)
    {
        $data = $this->dosenService->listDosen($request->all(), $request->query('limit', 10));

        return $this->successPaginationResponse(
            $data->items(),
            [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ],
            'List dosen berhasil diambil'
        );
    }

    #[OA\Post(
        path: '/admin/dosen',
        operationId: 'storeDosen',
        summary: 'Create a new lecturer',
        tags: ['Admin - Dosens'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreDosenRequest'))]
    #[OA\Response(response: 201, description: 'Dosen berhasil ditambahkan')]
    public function store(StoreDosenRequest $request)
    {
        try {
            $data = $request->validated();
            $dosen = $this->dosenService->create($data);
            
            if ($request->filled('minors_id')) {
                $dosen->minors()->attach($request->minors_id);
            }
            
            return $this->successResponse($dosen->load(['major', 'minors']), 'Dosen berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal menambahkan dosen');
        }
    }

    #[OA\Put(
        path: '/admin/dosen/{id}',
        operationId: 'updateDosen',
        summary: 'Update an existing lecturer',
        tags: ['Admin - Dosens'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateDosenRequest'))]
    #[OA\Response(response: 200, description: 'Dosen berhasil diperbarui')]
    public function update(UpdateDosenRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $dosen = $this->dosenService->find($id);
            
            if (!$dosen) {
                return $this->errorResponse('Dosen tidak ditemukan', 'Gagal memperbarui dosen', 404);
            }
            
            $dosen->update($data);

            if ($request->filled('minors_id')) {
                $dosen->minors()->sync($request->minors_id);
            } else {
                $dosen->minors()->detach();
            }

            return $this->successResponse($dosen->load(['major', 'minors']), 'Dosen berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal memperbarui dosen');
        }
    }

    #[OA\Delete(
        path: '/admin/dosen/{id}',
        operationId: 'deleteDosen',
        summary: 'Delete a lecturer',
        tags: ['Admin - Dosens'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Dosen berhasil dihapus')]
    public function destroy($id)
    {
        try {
            $dosen = $this->dosenService->find($id);

            if (!$dosen) {
                return $this->errorResponse('Dosen tidak ditemukan', 'Gagal menghapus dosen', 404);
            }

            $this->dosenService->delete($id);

            return $this->successResponse(null, 'Dosen berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal menghapus dosen');
        }
    }
}
