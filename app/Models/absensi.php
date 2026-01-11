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
            'jam_masuk',
            'jam_pulang',
            'status',
            'keterangan',
            'foto_masuk',
            'foto_pulang',
            'koordinat_masuk',
            'koordinat_pulang',
        ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'tanggal'    => 'date',
        'jam_masuk'  => 'string',
        'jam_pulang' => 'string',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}
