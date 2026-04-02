<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'avatar',
        'program_studi',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function riwayatKlasifikasi()
    {
        return $this->hasMany(RiwayatKlasifikasi::class);
    }
}
