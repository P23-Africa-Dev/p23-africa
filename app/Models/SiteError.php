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

    /**
     * Plain-text dump for clipboard / support handoff.
     */
    public function fullErrorLog(): string
    {
        return implode("\n", array_filter([
            'Time: '.$this->created_at?->format('d M Y H:i:s'),
            'Status: '.$this->status,
            'Method/Path: '.$this->method.' '.$this->path.($this->query ? '?'.$this->query : ''),
            'Exception: '.$this->exception_class,
            'Location: '.($this->file ?: 'n/a').':'.($this->line ?: 'n/a'),
            'User: '.($this->user ? $this->user->name.' ('.$this->user->email.')' : 'Guest'),
            '',
            'Message:',
            $this->message,
            '',
            'Stack:',
            $this->trace ?: '(none)',
        ], fn ($line) => $line !== null));
    }
}
