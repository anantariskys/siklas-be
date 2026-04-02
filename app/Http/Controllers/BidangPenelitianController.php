<?php

namespace App\Http\Controllers;

use App\Services\BidangPenelitianService;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

class BidangPenelitianController extends Controller
{
    use ApiResponse;

    protected BidangPenelitianService $bidangPenelitianService;

    public function __construct(BidangPenelitianService $bidangPenelitianService)
    {
        $this->bidangPenelitianService = $bidangPenelitianService;
    }

    #[OA\Get(
        path: '/bidang-penelitian/options',
        operationId: 'getBidangPenelitianOptions',
        summary: 'Get all fields of study as options',
        tags: ['Public - Bidang Penelitian'],
        security: []
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful operation',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Bidang penelitian berhasil diambil'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
            ]
        )
    )]
    public function options()
    {
        $bidangPenelitians = $this->bidangPenelitianService->all();
        return $this->successResponse($bidangPenelitians, 'Bidang penelitian berhasil diambil');
    }
}
