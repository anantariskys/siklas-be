<?php

namespace App\Http\Controllers;

use App\Services\RiwayatKlasifikasiService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RiwayatKlasifikasiController extends Controller
{
    use ApiResponse;

    protected RiwayatKlasifikasiService $riwayatService;

    public function __construct(RiwayatKlasifikasiService $riwayatService)
    {
        $this->riwayatService = $riwayatService;
    }

    #[OA\Get(
        path: '/riwayat-klasifikasi',
        operationId: 'listRiwayatKlasifikasi',
        summary: 'List classification history',
        description: 'Get classification history based on the authenticated user role. Admin sees all, Kaprodi sees program-specific history, and others see their own.',
        tags: ['Riwayat Klasifikasi'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'limit', in: 'query', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Daftar riwayat klasifikasi berhasil diambil'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
            ]
        )
    )]
    public function index(Request $request)
    {
        $data = $this->riwayatService->listRiwayat($request->user(), $request->all(), $request->query('limit', 10));

        return $this->successPaginationResponse(
            $data->items(),
            [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ],
            'Daftar riwayat klasifikasi berhasil diambil'
        );
    }

    #[OA\Post(
        path: '/riwayat-klasifikasi',
        operationId: 'storeRiwayatKlasifikasi',
        summary: 'Create a new classification record',
        tags: ['Riwayat Klasifikasi'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['prediksi_topik'],
            properties: [
                new OA\Property(property: 'user_id', type: 'integer', nullable: true),
                new OA\Property(property: 'judul', type: 'string', nullable: true),
                new OA\Property(property: 'abstrak', type: 'string', nullable: true),
                new OA\Property(property: 'prediksi_topik', type: 'string'),
                new OA\Property(property: 'confidence_score', type: 'number', format: 'float', nullable: true),
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Riwayat klasifikasi berhasil disimpan')]
    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['mahasiswa', 'dosen'])) {
            return $this->errorResponse('Hanya mahasiswa dan dosen yang dapat melakukan klasifikasi', 'Akses ditolak', 403);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'judul' => 'nullable|string',
            'abstrak' => 'nullable|string',
            'prediksi_topik' => 'required|string',
            'confidence_score' => 'nullable|numeric',
        ]);

        try {
            $data = array_merge($validated, ['diklasifikasi_pada' => now()]);
            // If user_id is not provided, use the authenticated user's ID
            if (!isset($data['user_id'])) {
                $data['user_id'] = $request->user()->id;
            }
            $riwayat = $this->riwayatService->create($data);

            return $this->successResponse($riwayat, 'Riwayat klasifikasi berhasil disimpan', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal menyimpan riwayat klasifikasi', 500);
        }
    }

    #[OA\Get(
        path: '/riwayat-klasifikasi/{id}',
        operationId: 'getRiwayatKlasifikasi',
        summary: 'Get a single classification record',
        tags: ['Riwayat Klasifikasi'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Successful operation')]
    #[OA\Response(response: 404, description: 'Riwayat klasifikasi tidak ditemukan')]
    public function show($id)
    {
        $riwayat = $this->riwayatService->find($id);

        if (!$riwayat) {
            return $this->errorResponse('Riwayat klasifikasi tidak ditemukan', null, 404);
        }

        return $this->successResponse($riwayat->load('user'), 'Detail riwayat klasifikasi berhasil diambil');
    }

    #[OA\Delete(
        path: '/riwayat-klasifikasi/{id}',
        operationId: 'deleteRiwayatKlasifikasi',
        summary: 'Delete a classification record',
        tags: ['Riwayat Klasifikasi'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Successful operation')]
    #[OA\Response(response: 404, description: 'Riwayat klasifikasi tidak ditemukan')]
    public function destroy($id)
    {
        try {
            $riwayat = $this->riwayatService->find($id);
            if (!$riwayat) {
                return $this->errorResponse('Riwayat klasifikasi tidak ditemukan', null, 404);
            }
            $this->riwayatService->delete($id);
            return $this->successResponse(null, 'Riwayat klasifikasi berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal menghapus riwayat klasifikasi', 500);
        }
    }
}
