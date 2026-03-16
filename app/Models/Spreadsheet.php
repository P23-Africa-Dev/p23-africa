<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spreadsheet extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'original_filename',
        'file_path',
        'sheet_data',
        'created_by',
        'last_edited_by',
        'last_edited_at',
    ];

    protected $casts = [
        'last_edited_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function lastEditor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'last_edited_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeForOrganization($query, ?int $orgId)
    {
        if ($orgId) {
            return $query->where('organization_id', $orgId);
        }
        return $query->whereNull('organization_id');
    }
}
