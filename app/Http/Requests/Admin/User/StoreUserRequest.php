<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreUserRequest',
    required: ['name', 'username', 'email', 'password', 'role'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'johndoe@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
        new OA\Property(property: 'role', type: 'string', enum: ['mahasiswa', 'admin', 'kaprodi', 'dosen'], example: 'mahasiswa'),
        new OA\Property(property: 'program_studi', type: 'string', enum: ['PTI', 'SI', 'TI', 'TIF', 'TEKKOM', 'NULL'], nullable: true),
    ]
)]
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
            'role' => 'required|in:mahasiswa,kaprodi,dosen,admin',
            'program_studi' => 'nullable|in:PTI,SI,TI,TIF,TEKKOM,NULL',
        ];
    }
}
