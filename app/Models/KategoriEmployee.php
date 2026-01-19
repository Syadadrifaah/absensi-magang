<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class KategoriEmployee extends Model
{
    //
    protected $table = 'kategori_employees';

    protected $fillable = [
        'nama_kategori'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
