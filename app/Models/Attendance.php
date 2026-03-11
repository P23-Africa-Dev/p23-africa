<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'date',
        'signed_in_at',
        'signed_out_at',
        'sign_in_ip',
        'sign_out_ip',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'signed_in_at' => 'datetime',
        'signed_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if the user has signed in today.
     */
    public static function hasSignedInToday($userId): bool
    {
        return static::where('user_id', $userId)
            ->where('date', today())
            ->whereNotNull('signed_in_at')
            ->exists();
    }

    /**
     * Check if the user has signed out today.
     */
    public static function hasSignedOutToday($userId): bool
    {
        return static::where('user_id', $userId)
            ->where('date', today())
            ->whereNotNull('signed_out_at')
            ->exists();
    }

    /**
     * Get today's attendance for a user.
     */
    public static function todayForUser($userId)
    {
        return static::where('user_id', $userId)
            ->where('date', today())
            ->first();
    }
}
