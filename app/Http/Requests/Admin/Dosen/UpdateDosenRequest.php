<?php

namespace App\Http\Requests\Admin\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateDosenRequest',
    properties: [
        new OA\Property(property: 'nama', type: 'string', example: 'Dr. John Doe Updated'),
        new OA\Property(property: 'gelar_awal', type: 'string', nullable: true),
        new OA\Property(property: 'gelar_akhir', type: 'string', nullable: true),
        new OA\Property(property: 'bidang_penelitian_major_id', type: 'integer'),
        new OA\Property(property: 'minors_id', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'gelar_awal' => 'nullable|string|max:100',
            'gelar_akhir' => 'nullable|string|max:100',
            'bidang_penelitian_major_id' => 'required|exists:bidang_penelitians,id',
            'minors_id' => 'nullable|array',
            'minors_id.*' => 'exists:bidang_penelitians,id',
        ];
    }
}
