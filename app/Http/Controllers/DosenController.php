<?php

namespace App\Http\Controllers;

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
        path: '/dosen/{bidangPenelitian}',
        operationId: 'getDosenByBidang',
        summary: 'Get lecturers by research field slug',
        tags: ['Public - Dosens'],
        security: []
    )]
    #[OA\Parameter(name: 'bidangPenelitian', in: 'path', required: true, description: 'Slug of the research field', schema: new OA\Schema(type: 'string'))]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Dosen retrieved successfully'),
                new OA\Property(property: 'data', type: 'object'),
            ]
        )
    )]
    #[OA\Response(response: 404, description: 'Bidang penelitian tidak ditemukan')]
    public function getDosenByBidang(Request $request, $bidangPenelitian)
    {
        $data = $this->dosenService->getByBidang($bidangPenelitian);

        if (!$data) {
            return $this->errorResponse('Bidang penelitian tidak ditemukan', 404);
        }

        return $this->successResponse($data, 'Dosen retrieved successfully');
    }
}
