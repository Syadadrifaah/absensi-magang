<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
        protected $table = 'user_activity_logs';
        protected $fillable = [
            'user_id',
            'action',       
            'table_name',   
            'record_id', 
            'description',
            'ip_address',
            'user_agent',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
