<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangPenelitian extends Model
{
    protected $table = 'bidang_penelitians';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];

  public function dosenMajor()
    {
        return $this->hasMany(Dosen::class, 'bidang_penelitian_major_id');
    }

    public function dosenMinor()
    {
        return $this->hasMany(Dosen::class, 'bidang_penelitian_minor_id');
    }
}
