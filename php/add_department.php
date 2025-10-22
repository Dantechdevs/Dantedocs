<?php
// php/add_department.php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'msg' => 'Invalid method'], 405);
}

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    json_response(['ok' => false, 'msg' => 'Name required'], 400);
}

try {
    $pdo = getPDO();
    $nameNormalized = mb_strtoupper($name);

    $stmt = $pdo->prepare("SELECT id FROM departments WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => $nameNormalized]);
    if ($stmt->fetch()) {
        json_response(['ok' => true, 'msg' => 'Department already exists', 'exists' => true]);
    }

    $ins = $pdo->prepare("INSERT INTO departments (name, created_at) VALUES (:name, NOW())");
    $ins->execute([':name' => $nameNormalized]);
    json_response(['ok' => true, 'msg' => 'Department added', 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    json_response(['ok' => false, 'msg' => 'Server error: ' . $e->getMessage()], 500);
}
