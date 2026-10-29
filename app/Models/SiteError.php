<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteError extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'path',
        'query',
        'status',
        'exception_class',
        'message',
        'file',
        'line',
        'trace',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exceptionName(): string
    {
        $name = $this->exception_class;

        return ($pos = strrpos($name, '\\')) === false ? $name : substr($name, $pos + 1);
    }
}
