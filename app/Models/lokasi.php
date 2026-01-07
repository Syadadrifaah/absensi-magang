<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class lokasi extends Model
{
    //
    protected $table = 'tbl_lokasi';

    protected $fillable =   ['nama_lokasi',
                            'koordinat',
                            'radius',
                            'is_active'];

     protected $appends = ['latitude', 'longitude'];

    public function getLatitudeAttribute()
    {
        if (!$this->koordinat) return null;
        return explode(',', $this->koordinat)[0];
    }

    public function getLongitudeAttribute()
    {
        if (!$this->koordinat) return null;
        return explode(',', $this->koordinat)[1];
    }


}
