<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PengaturanAbsensi extends Model
{
    //
     protected $table = 'tbl_pengaturan_absensi';

    protected $fillable = [
        'nama',
        'jam_masuk_mulai',
        'jam_masuk_selesai',
        'jam_pulang_mulai',
        'jam_pulang_selesai',
        'aktif',
    ];

    /* ================= MUTATOR (SIMPAN KE DB) ================= */

    private function normalizeTime($value)
    {
        // jika H:i → jadi H:i:00
        return strlen($value) === 5 ? $value . ':00' : $value;
    }

    public function setJamMasukMulaiAttribute($value)
    {
        $this->attributes['jam_masuk_mulai'] = $this->normalizeTime($value);
    }

    public function setJamMasukSelesaiAttribute($value)
    {
        $this->attributes['jam_masuk_selesai'] = $this->normalizeTime($value);
    }

    public function setJamPulangMulaiAttribute($value)
    {
        $this->attributes['jam_pulang_mulai'] = $this->normalizeTime($value);
    }

    public function setJamPulangSelesaiAttribute($value)
    {
        $this->attributes['jam_pulang_selesai'] = $this->normalizeTime($value);
    }

    /* ================= ACCESSOR (TAMPIL KE VIEW) ================= */

    public function getJamMasukMulaiAttribute($value)
    {
        return substr($value, 0, 5); // H:i
    }

    public function getJamMasukSelesaiAttribute($value)
    {
        return substr($value, 0, 5);
    }

    public function getJamPulangMulaiAttribute($value)
    {
        return substr($value, 0, 5);
    }

    public function getJamPulangSelesaiAttribute($value)
    {
        return substr($value, 0, 5);
    }
}
