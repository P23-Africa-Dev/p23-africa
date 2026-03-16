<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SpreadsheetPasscode extends Model
{
    protected $fillable = [
        'organization_id',
        'passcode',
        'created_by',
        'regenerated_at',
    ];

    protected $casts = [
        'regenerated_at' => 'datetime',
    ];

    /**
     * The passcode is always stored hashed.
     * The PLAIN-TEXT passcode is only visible immediately after creation / regeneration.
     */
    protected $hidden = ['passcode'];

    // ─── Relationships ────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Generate a random 6-digit passcode, hash-store it, and return the plain text.
     */
    public static function generateForOrg(int $orgId, int $creatorId): array
    {
        $plain = strtoupper(Str::random(6)); // e.g. "A3K9FX"

        $record = self::updateOrCreate(
            ['organization_id' => $orgId],
            [
                'passcode'       => Hash::make($plain),
                'created_by'     => $creatorId,
                'regenerated_at' => now(),
            ]
        );

        return ['record' => $record, 'plain' => $plain];
    }

    /**
     * Verify a plain-text passcode against the stored hash.
     */
    public function verify(string $plain): bool
    {
        return Hash::check($plain, $this->passcode);
    }

    /**
     * Get (or null) for an org.
     */
    public static function forOrg(int $orgId): ?self
    {
        return self::where('organization_id', $orgId)->first();
    }
}
