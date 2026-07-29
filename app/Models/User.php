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
        'permissions',
        'organization_id',
        'whatsapp_account_id',
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

    /**
     * Organization relation
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * The WhatsApp account this user has currently selected as their active sender.
     */
    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsAppAccount::class, 'whatsapp_account_id');
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

    /**
     * Determine whether this user can bypass organization scoping globally.
     */
    public function hasCrossOrganizationAccess(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Determine whether organization scoping should be applied for this user.
     */
    public function appliesOrganizationScope(): bool
    {
        return !$this->hasCrossOrganizationAccess();
    }

    /**
     * Determine whether a given organization context is accessible to this user.
     */
    public function canAccessOrganization(?int $organizationId): bool
    {
        if (!$this->appliesOrganizationScope()) {
            return true;
        }

        return $this->organization_id === $organizationId;
    }
}
