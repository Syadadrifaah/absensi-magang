<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Position extends Model
{
    protected $table = 'positions';
    protected $fillable = ['name', 'level'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
}
