<?php
// php/add_category.php
require_once __DIR__ . '/db_connect.php';

/**
 * JSON response helper
 */
function json_response($data, $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Ensure POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'msg' => 'Invalid request method'], 405);
}

// Validate input
$name = trim($_POST['name'] ?? '');
if ($name === '') {
    json_response(['ok' => false, 'msg' => 'Category name is required'], 400);
}

try {
    $pdo = getPDO();

    // Normalize name (optional)
    $nameNormalized = mb_strtoupper($name);

    // Check if category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => $nameNormalized]);

    if ($stmt->fetch()) {
        json_response([
            'ok' => false,
            'msg' => 'Category already exists',
            'exists' => true
        ]);
    }

    // Insert category (no created_at column)
    $insert = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $insert->execute([':name' => $nameNormalized]);

    json_response([
        'ok' => true,
        'msg' => 'Category added successfully',
        'id' => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    json_response([
        'ok' => false,
        'msg' => 'Server error: ' . $e->getMessage()
    ], 500);
}
