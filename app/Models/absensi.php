<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absensi extends Model
{
    //
    protected $table = 'tbl_absensi';

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'tanggal',
        'jam',
        'status',
        'keterangan',
        'koordinat_user',
        'foto',
    ];
}
