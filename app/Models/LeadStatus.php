<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LeadStatus extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'color',
        'position',
        'is_default',
        'is_won',
        'is_lost',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_won'     => 'boolean',
        'is_lost'    => 'boolean',
        'position'   => 'integer',
    ];

    /**
     * Auto-generate slug on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($status) {
            if (empty($status->slug)) {
                $status->slug = Str::slug($status->name);
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope statuses to an organization (null = global / super-admin).
     */
    public function scopeForOrganization($query, ?int $orgId)
    {
        if ($orgId) {
            return $query->where('organization_id', $orgId);
        }

        return $query->whereNull('organization_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Seed the default set of statuses for a given organization.
     * Pass null for the global (super-admin) set.
     */
    public static function seedDefaults(?int $orgId = null): void
    {
        $defaults = [
            ['name' => 'New',           'slug' => 'new',           'color' => 'info',      'position' => 1, 'is_default' => true,  'is_won' => false, 'is_lost' => false],
            ['name' => 'Contacted',     'slug' => 'contacted',     'color' => 'primary',   'position' => 2, 'is_default' => false, 'is_won' => false, 'is_lost' => false],
            ['name' => 'Qualified',     'slug' => 'qualified',     'color' => 'warning',   'position' => 3, 'is_default' => false, 'is_won' => false, 'is_lost' => false],
            ['name' => 'Unqualified',   'slug' => 'unqualified',   'color' => 'secondary', 'position' => 4, 'is_default' => false, 'is_won' => false, 'is_lost' => false],
            ['name' => 'Proposal Sent', 'slug' => 'proposal',      'color' => 'secondary', 'position' => 5, 'is_default' => false, 'is_won' => false, 'is_lost' => false],
            ['name' => 'Negotiation',   'slug' => 'negotiation',   'color' => 'dark',      'position' => 6, 'is_default' => false, 'is_won' => false, 'is_lost' => false],
            ['name' => 'Won',           'slug' => 'won',           'color' => 'success',   'position' => 7, 'is_default' => false, 'is_won' => true,  'is_lost' => false],
            ['name' => 'Lost',          'slug' => 'lost',          'color' => 'danger',    'position' => 8, 'is_default' => false, 'is_won' => false, 'is_lost' => true],
        ];

        foreach ($defaults as $d) {
            self::firstOrCreate(
                ['organization_id' => $orgId, 'slug' => $d['slug']],
                array_merge($d, ['organization_id' => $orgId])
            );
        }
    }

    /**
     * Get an ordered key-value map [ slug => name ] for an organization.
     */
    public static function getMapForOrg(?int $orgId): array
    {
        return self::forOrganization($orgId)
            ->orderBy('position')
            ->pluck('name', 'slug')
            ->toArray();
    }

    /**
     * Get an ordered collection of statuses for an organization.
     */
    public static function getForOrg(?int $orgId)
    {
        return self::forOrganization($orgId)
            ->orderBy('position')
            ->get();
    }

    /**
     * Get the color map [ slug => color ] for an organization.
     */
    public static function getColorMapForOrg(?int $orgId): array
    {
        return self::forOrganization($orgId)
            ->orderBy('position')
            ->pluck('color', 'slug')
            ->toArray();
    }

    /**
     * Get the default status slug for new leads in an organization.
     */
    public static function getDefaultSlug(?int $orgId): string
    {
        $default = self::forOrganization($orgId)
            ->where('is_default', true)
            ->first();

        return $default ? $default->slug : 'new';
    }

    /**
     * Check if a slug represents a "won" status.
     */
    public static function isWonStatus(string $slug, ?int $orgId): bool
    {
        return self::forOrganization($orgId)
            ->where('slug', $slug)
            ->where('is_won', true)
            ->exists();
    }

    /**
     * Check if a slug represents a "lost" status.
     */
    public static function isLostStatus(string $slug, ?int $orgId): bool
    {
        return self::forOrganization($orgId)
            ->where('slug', $slug)
            ->where('is_lost', true)
            ->exists();
    }

    /**
     * Check if a status is a terminal state (won or lost).
     */
    public static function isTerminal(string $slug, ?int $orgId): bool
    {
        return self::forOrganization($orgId)
            ->where('slug', $slug)
            ->where(function ($q) {
                $q->where('is_won', true)->orWhere('is_lost', true);
            })
            ->exists();
    }

    /**
     * Get the slugs for all "lost" statuses in an organization.
     */
    public static function getLostSlugs(?int $orgId): array
    {
        return self::forOrganization($orgId)
            ->where('is_lost', true)
            ->pluck('slug')
            ->toArray();
    }

    /**
     * Get the slugs for all "won" statuses in an organization.
     */
    public static function getWonSlugs(?int $orgId): array
    {
        return self::forOrganization($orgId)
            ->where('is_won', true)
            ->pluck('slug')
            ->toArray();
    }

    /**
     * Get slugs for terminal (won + lost) statuses.
     */
    public static function getTerminalSlugs(?int $orgId): array
    {
        return self::forOrganization($orgId)
            ->where(function ($q) {
                $q->where('is_won', true)->orWhere('is_lost', true);
            })
            ->pluck('slug')
            ->toArray();
    }
}
