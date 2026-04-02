<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateUserRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe Updated'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'johndoe.updated@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', nullable: true),
        new OA\Property(property: 'role', type: 'string', enum: ['mahasiswa', 'admin', 'kaprodi', 'dosen']),
        new OA\Property(property: 'program_studi', type: 'string', enum: ['PTI', 'SI', 'TI', 'TIF', 'TEKKOM', 'NULL'], nullable: true),
    ]
)]
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        return [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $userId,
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'sometimes|string|min:8|max:255',
            'role' => 'sometimes|in:mahasiswa,kaprodi,dosen,admin',
            'program_studi' => 'sometimes|in:PTI,SI,TI,TIF,TEKKOM,NULL',
        ];
    }
}
