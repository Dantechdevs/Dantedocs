<?php
// php/delete_document.php
require_once __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'msg' => 'Invalid method'], 405);
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    json_response(['ok' => false, 'msg' => 'Missing or invalid ID'], 400);
}

try {
    $pdo = getPDO();

    // fetch document record
    $stmt = $pdo->prepare("SELECT filepath FROM documents WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $doc = $stmt->fetch();

    if (!$doc) {
        json_response(['ok' => false, 'msg' => 'Document not found'], 404);
    }

    $filepath = realpath(__DIR__ . '/../' . $doc['filepath']);

    // delete record from DB
    $del = $pdo->prepare("DELETE FROM documents WHERE id = :id");
    $del->execute([':id' => $id]);

    // delete file from disk
    if ($filepath && file_exists($filepath)) {
        @unlink($filepath);
    }

    json_response(['ok' => true, 'msg' => 'Document deleted']);
} catch (Exception $e) {
    json_response(['ok' => false, 'msg' => 'Error: ' . $e->getMessage()], 500);
}
