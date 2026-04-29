<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$db = new SQLite3(__DIR__ . '/../../db/portfolio.db');

function query($sql, $params = []) {
    global $db;
    $stmt = $db->prepare($sql);
    foreach($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    return $stmt->execute();
}
?>
