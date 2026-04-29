<?php
function get_db() {
    $db_path = __DIR__ . '/../db/portfolio.db';
    if (!file_exists($db_path)) return null;
    return new SQLite3($db_path);
}

function fetch_projects($limit = 10) {
    $db = get_db();
    if (!$db) return [];
    $res = $db->query("SELECT * FROM projects ORDER BY id DESC LIMIT $limit");
    $data = [];
    while($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function fetch_blogs($limit = 10) {
    $db = get_db();
    if (!$db) return [];
    $res = $db->query("SELECT * FROM blogs ORDER BY id DESC LIMIT $limit");
    $data = [];
    while($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function fetch_skills() {
    $db = get_db();
    if (!$db) return [];
    $res = $db->query("SELECT * FROM skills ORDER BY category, name");
    $data = [];
    while($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}
?>
