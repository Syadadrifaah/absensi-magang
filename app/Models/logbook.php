<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logbook extends Model
{
    //
    protected $table = 'tbl_logbook';

    protected $fillable = [
        'user_id',
        'tanggal',
        'kegiatan',
    ];
}
