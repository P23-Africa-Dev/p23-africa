<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_DISABLED = 'disabled';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'language',
        'template_format',
        'template_json',
        'rendered_content',
        'preview_content',
        'editor_metadata',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'template_json' => 'array',
            'editor_metadata' => 'array',
        ];
    }

    public function eventCampaigns(): HasMany
    {
        return $this->hasMany(EventWhatsAppCampaign::class, 'template_id');
    }
}
