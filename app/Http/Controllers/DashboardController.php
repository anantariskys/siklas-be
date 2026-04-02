<?php

namespace App\Http\Controllers;

use App\Services\RiwayatKlasifikasiService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    use ApiResponse;

    protected RiwayatKlasifikasiService $riwayatService;

    public function __construct(RiwayatKlasifikasiService $riwayatService)
    {
        $this->riwayatService = $riwayatService;
    }

    #[OA\Get(
        path: '/dashboard',
        operationId: 'getDashboardStats',
        summary: 'Get dashboard statistics',
        description: 'Get dashboard statistics based on the authenticated user role. Admin sees global stats, Kaprodi sees program-specific stats, and others see their own stats.',
        tags: ['Dashboard'],
        security: [['sanctum' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Data dashboard berhasil diambil'),
                new OA\Property(property: 'data', type: 'object'),
            ]
        )
    )]
    public function index(Request $request)
    {
        try {
            $stats = $this->riwayatService->getDashboardStats($request->user());
            return $this->successResponse($stats, 'Data dashboard berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Gagal mengambil data dashboard');
        }
    }
}
