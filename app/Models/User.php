<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'suspended_at',
        'role',
        'permissions'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'permissions' => 'array',
            'password' => 'hashed',
        ];
    }

    // protected static $logAttributes = ['name', 'email'];
    // protected static $logName = 'user';


    // protected static function booted()
    // {
    //     static::addGlobalScope('notSuspended', function ($query) {
    //         $query->where('status', '!=', 'suspended');
    //     });
    // }



    public function isSuspended()
    {
        return !is_null($this->suspended_at);
    }


    public function hasPermission($permission)
    {
        // Admins have all permissions
        if ($this->role === 'admin') {
            return true;
        }

        // For staff, check permissions (assuming a many-to-many relationship)
        return $this->permissions()->where('name', $permission)->exists();
    }
}
