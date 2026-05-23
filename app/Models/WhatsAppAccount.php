<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class WhatsAppAccount extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_accounts';

    protected $fillable = [
        'business_name',
        'phone_number',
        'phone_number_id',
        'waba_id',
        'access_token',
        'webhook_verify_token',
        'status',
        'display_name_status',
        'profile_sync_status',
        'last_profile_sync',
        'meta_profile_id',
        'profile_image_url',
    ];

    protected $casts = [
        'last_profile_sync' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    protected function accessToken(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) {
                    return null;
                }

                $raw = (string) $value;

                try {
                    return Crypt::decryptString($raw);
                } catch (DecryptException) {
                    // Backward compatibility for legacy/plaintext tokens in existing records.
                    return trim($raw, " \t\n\r\0\x0B\"'");
                }
            },
            set: function ($value) {
                if (is_null($value)) {
                    return null;
                }

                $token = trim((string) $value);

                if (str_starts_with(strtolower($token), 'bearer ')) {
                    $token = substr($token, 7);
                }

                $token = trim($token, " \t\n\r\0\x0B\"'");

                if ($token === '') {
                    return null;
                }

                return Crypt::encryptString($token);
            }
        );
    }
}
