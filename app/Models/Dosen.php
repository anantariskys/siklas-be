<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dosen extends Model
{
      use HasFactory;

  
    protected $table = 'dosens';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'bidang_penelitian_major_id',
        'gelar_awal',
        'gelar_akhir',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid();
            }
        });
    }


    public function major()
    {
        return $this->belongsTo(BidangPenelitian::class, 'bidang_penelitian_major_id');
    }

     public function minors()
    {
        return $this->belongsToMany(
            BidangPenelitian::class,
            'dosen_minor_bidang',
            'dosen_id',
            'bidang_penelitian_id'
        );
    }
}
