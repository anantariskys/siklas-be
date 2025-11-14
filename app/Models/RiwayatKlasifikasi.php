<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKlasifikasi extends Model
{
     use HasFactory;

    protected $table = 'riwayat_klasifikasis';

    protected $fillable = [
        'user_id',
        'judul',
        'abstrak',
        'prediksi_topik',
        'confidence_score',
        'diklasifikasi_pada',
    ];

    protected $dates = ['diklasifikasi_pada'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
