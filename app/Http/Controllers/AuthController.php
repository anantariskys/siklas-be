<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;
  public function socialLogin(Request $request)
{
    $request->validate([
        'google_id' => 'required|string',
        'email' => 'required|email',
        'name' => 'required|string',
        'avatar' => 'nullable|string',
    ]);

    $user = User::where('google_id', $request->google_id)
                ->orWhere('email', $request->email)
                ->first();

    if (!$user) {
        $user = User::create([
            'google_id' => $request->google_id,
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $request->avatar,
            'password' => bcrypt('password'),
        ]);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->successResponse([
        'user' => $user,
        'token' => $token,
    ]);
}

}   
