<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Auto-seed default lead statuses when a new organization is created.
     */
    protected static function booted(): void
    {
        static::created(function (Organization $organization) {
            LeadStatus::seedDefaults($organization->id);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function leadStatuses()
    {
        return $this->hasMany(LeadStatus::class);
    }
}
