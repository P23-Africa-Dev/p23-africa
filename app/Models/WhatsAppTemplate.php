<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'language',
        'template_json',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'template_json' => 'array',
        ];
    }
}
