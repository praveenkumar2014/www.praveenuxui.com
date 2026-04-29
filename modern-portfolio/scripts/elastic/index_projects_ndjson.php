<?php
declare(strict_types=1);

/**
 * Usage:
 *   php scripts/elastic/index_projects_ndjson.php [dbPath] [indexName]
 *
 * Outputs Bulk API NDJSON to STDOUT.
 */

$dbPath = $argv[1] ?? (__DIR__ . '/../../db/portfolio.db');
$indexName = $argv[2] ?? 'portfolio-projects';

if (!is_file($dbPath)) {
    fwrite(STDERR, "DB not found: {$dbPath}\n");
    exit(1);
}

$db = new SQLite3($dbPath, SQLITE3_OPEN_READONLY);

$query = $db->query('SELECT id, title, category, image, link, description, created_at FROM projects ORDER BY id ASC');
if ($query === false) {
    fwrite(STDERR, "Failed to read projects table.\n");
    exit(1);
}

while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $id = (int)($row['id'] ?? 0);
    if ($id <= 0) {
        continue;
    }

    $doc = [
        'id' => $id,
        'title' => (string)($row['title'] ?? ''),
        'category' => $row['category'] !== null ? (string)$row['category'] : null,
        'image' => $row['image'] !== null ? (string)$row['image'] : null,
        'link' => $row['link'] !== null ? (string)$row['link'] : null,
        'description' => $row['description'] !== null ? (string)$row['description'] : null,
        'created_at' => $row['created_at'] !== null ? (string)$row['created_at'] : null,
    ];

    echo json_encode(['index' => ['_index' => $indexName, '_id' => (string)$id]], JSON_UNESCAPED_SLASHES) . "\n";
    echo json_encode($doc, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
}

