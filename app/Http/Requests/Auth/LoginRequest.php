<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginRequest',
    required: ['username', 'password'],
    properties: [
        new OA\Property(property: 'username', type: 'string', example: 'admin'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
    ]
)]
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required',
        ];
    }
}
