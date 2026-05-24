<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppContact extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_contacts';

    protected $fillable = [
        'name',
        'phone',
        'country_code',
        'email',
        'company',
        'notes',
        'avatar',
        'profile_picture',
        'is_whatsapp',
        'validation_status',
        'validated_at',
        'validation_cache_until',
        'last_chat_at',
        'unsaved_contact',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_whatsapp' => 'boolean',
            'validated_at' => 'datetime',
            'validation_cache_until' => 'datetime',
            'last_chat_at' => 'datetime',
            'unsaved_contact' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(WhatsAppConversation::class, 'contact_id');
    }
}
