<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('Email tidak ditemukan', 404);
        }

        // Pastikan role admin saja yang bisa login
        if ($user->role !== 'admin') {
            return $this->errorResponse('Akses ditolak, akun bukan admin', 403);
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Password salah', 401);
        }

        // Generate Sanctum Token
        $token = $user->createToken('admin_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Login berhasil');
    }
}
