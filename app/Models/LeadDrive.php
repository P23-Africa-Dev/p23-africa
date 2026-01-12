<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LeadDrive extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($drive) {
            if (empty($drive->slug)) {
                $drive->slug = Str::slug($drive->name);
            }
        });
    }

    /**
     * Get all leads in this drive
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'drive_id');
    }

    /**
     * Get the default drive
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Get leads count
     */
    public function getLeadsCountAttribute(): int
    {
        return $this->leads()->count();
    }

    /**
     * Get total value of leads in this drive
     */
    public function getTotalValueAttribute(): float
    {
        return $this->leads()->sum('estimated_value') ?? 0;
    }
}
