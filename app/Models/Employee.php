<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\KategoriEmployee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';
    protected $fillable = [
        'user_id',
        'kategori_id',
        'position_id',
        'nip',
        'name',
        'department',
        'email',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriEmployee::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

}
