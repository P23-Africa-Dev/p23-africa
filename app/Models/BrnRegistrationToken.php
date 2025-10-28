<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrnRegistrationToken extends Model
{
    protected $fillable = [
        'token',
        'encrypted_data',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the token is valid (not expired)
     */
    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
