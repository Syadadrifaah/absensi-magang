<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* RELASI */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        // normalize tokens
        $tokens = array_map(function ($r) {
            return trim(strtolower((string) $r));
        }, $roles);

        // resolve current user's role name and id
        $roleName = $this->role ? strtolower($this->role->name) : null;
        $roleId = $this->role_id ? (string) $this->role_id : null;

        // if relation not loaded, try to fetch role name from role_id
        if ($roleName === null && $roleId !== null) {
            $r = Role::find((int) $roleId);
            if ($r) $roleName = strtolower($r->name);
        }

        foreach ($tokens as $t) {
            if (is_numeric($t) && $roleId !== null && $t === $roleId) {
                return true;
            }
            if ($roleName !== null && $t === $roleName) {
                return true;
            }
        }

        return false;
    }

    


    /**
     * The attributes that should be hidden for serialization.
     *
    //  * @var list<string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
