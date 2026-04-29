#!/usr/bin/env php
<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/portfolio.db';
if (!file_exists($dbPath)) {
    fwrite(STDERR, "DB not found at {$dbPath}\n");
    exit(1);
}

$db = new SQLite3($dbPath);

$updates = [
    'Anthropic' => 'https://svgl.app/library/anthropic_white.svg',
    'Hugging Face' => 'https://svgl.app/library/hugging_face.svg',
    'LangChain' => 'https://cdn.simpleicons.org/langchain',
    'Vercel' => 'https://svgl.app/library/vercel_dark.svg',
    'GitHub' => 'https://svgl.app/library/github_dark.svg',
    'React' => 'https://svgl.app/library/react_dark.svg',
];

$stmt = $db->prepare('UPDATE skills SET icon_url = :icon WHERE name = :name');
$changed = 0;
foreach ($updates as $name => $icon) {
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':icon', $icon, SQLITE3_TEXT);
    $stmt->execute();
    $changed += $db->changes();
}

echo "Updated icon_url for {$changed} row(s).\n";

