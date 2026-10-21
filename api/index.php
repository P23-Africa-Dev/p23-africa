<?php

$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$publicRoot = realpath(__DIR__ . '/../public');

if ($uri !== '/' && $publicRoot && ! str_contains($uri, "\0") && ! str_contains($uri, '..')) {
    $candidate = realpath($publicRoot . $uri);

    if ($candidate && str_starts_with($candidate, $publicRoot) && is_file($candidate)) {
        $extension = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));

        if ($extension !== 'php') {
            $types = [
                'css' => 'text/css; charset=UTF-8',
                'js' => 'application/javascript; charset=UTF-8',
                'mjs' => 'application/javascript; charset=UTF-8',
                'json' => 'application/json; charset=UTF-8',
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'ico' => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf',
                'eot' => 'application/vnd.ms-fontobject',
                'map' => 'application/json',
                'pdf' => 'application/pdf',
                'txt' => 'text/plain; charset=UTF-8',
                'xml' => 'application/xml',
                'webmanifest' => 'application/manifest+json',
            ];

            header('Content-Type: ' . ($types[$extension] ?? 'application/octet-stream'));
            header('Cache-Control: public, max-age=3600');
            readfile($candidate);

            return;
        }
    }
}

$database = '/tmp/database.sqlite';
$databaseSource = __DIR__ . '/../database/vercel.sqlite';

if (! is_file($database) && is_file($databaseSource)) {
    copy($databaseSource, $database);
}

putenv('DB_DATABASE=' . $database);
$_ENV['DB_DATABASE'] = $database;
$_SERVER['DB_DATABASE'] = $database;

if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

require __DIR__ . '/../public/index.php';
