<?php

$target = __DIR__ . '/vercel.sqlite';
$schema = __DIR__ . '/schema/vercel-preview.sql';

if (! is_file($schema)) {
    fwrite(STDERR, "Missing schema: {$schema}\n");
    exit(1);
}

if (is_file($target)) {
    unlink($target);
}

$pdo = new PDO('sqlite:' . $target);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec(file_get_contents($schema));

echo "Preview sqlite written to {$target}\n";
