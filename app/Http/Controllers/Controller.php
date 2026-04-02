<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Siklas API Documentation',
    description: 'API documentation for the Siklas project',
    contact: new OA\Contact(email: 'admin@example.com'),
    license: new OA\License(name: 'Apache 2.0', url: 'http://www.apache.org/licenses/LICENSE-2.0.html')
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST . '/api',
    description: 'Primary API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter your Sanctum token'
)]
#[OA\SecurityRequirement(
    securityRequirement: [
        'sanctum' => []
    ]
)]
abstract class Controller
{
    //
}
