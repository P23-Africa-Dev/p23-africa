<?php

namespace App\Support;

use App\Models\SiteError;
use Illuminate\Http\Request;
use Throwable;

class SiteErrorRecorder
{
    private const KEEP = 500;

    private static bool $recording = false;

    public function record(Throwable $e, ?Request $request, int $status = 500): void
    {
        if (self::$recording) {
            return;
        }

        self::$recording = true;

        try {
            SiteError::query()->create([
                'user_id' => $request?->user()?->id,
                'method' => substr(strtoupper($request?->getMethod() ?? 'GET'), 0, 10),
                'path' => mb_substr($request?->getPathInfo() ?? '', 0, 2000),
                'query' => $this->query($request),
                'status' => $status,
                'exception_class' => $e::class,
                'message' => mb_substr($e->getMessage(), 0, 2000),
                'file' => $e->getFile() !== '' ? mb_substr($e->getFile(), 0, 2000) : null,
                'line' => $e->getLine() ?: null,
                'trace' => $this->trace($e),
            ]);

            $this->prune();
        } catch (Throwable) {
            // A logging failure must not replace the visitor page.
        } finally {
            self::$recording = false;
        }
    }

    private function query(?Request $request): ?string
    {
        if ($request === null) {
            return null;
        }

        $hidden = [
            'password',
            'password_confirmation',
            'token',
            'secret',
            '_token',
            'api_key',
            'access_token',
            'card',
            'cvv',
            'authorization',
        ];

        $query = collect($request->query())->except($hidden)->all();
        if ($query === []) {
            return null;
        }

        return mb_substr(http_build_query($query), 0, 2000);
    }

    private function trace(Throwable $e): string
    {
        $lines = [];
        foreach (array_slice($e->getTrace(), 0, 40) as $index => $frame) {
            $file = $frame['file'] ?? '[internal]';
            $line = $frame['line'] ?? 0;
            $call = ($frame['class'] ?? '').($frame['type'] ?? '').($frame['function'] ?? '');
            $lines[] = '#'.$index.' '.$file.':'.$line.' '.$call;
        }

        return implode("\n", $lines);
    }

    private function prune(): void
    {
        $threshold = SiteError::query()->orderByDesc('id')->skip(self::KEEP - 1)->value('id');
        if ($threshold) {
            SiteError::query()->where('id', '<', $threshold)->delete();
        }
    }
}
