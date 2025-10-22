<?php
// php/upload_document_backend.php
require_once __DIR__ . '/db.php';
session_start();
require_once __DIR__ . '/session_check.php'; // ensures login

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'msg' => 'Invalid method'], 405);
}

$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$department = trim($_POST['department'] ?? '');
$uploaded_by = $_SESSION['username'] ?? 'Unknown User';

if ($title === '' || $category === '' || $department === '') {
    json_response(['ok' => false, 'msg' => 'Missing required fields'], 400);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    json_response(['ok' => false, 'msg' => 'File upload error'], 400);
}

$file = $_FILES['file'];
$size = (int)$file['size'];
$maxBytes = 50 * 1024 * 1024; // 50MB limit

if ($size > $maxBytes) {
    json_response(['ok' => false, 'msg' => 'File too large'], 400);
}

$catSafe = preg_replace('/[^A-Za-z0-9 _\-]/', '_', $category);
$deptSafe = preg_replace('/[^A-Za-z0-9 _\-]/', '_', $department);

$root = UPLOAD_ROOT;
$destDir = "$root/$catSafe/$deptSafe";
if (!is_dir($destDir)) mkdir($destDir, 0755, true);

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$base = pathinfo($file['name'], PATHINFO_FILENAME);
$uniq = bin2hex(random_bytes(6));
$filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $base) . "_$uniq.$ext";
$destPath = "$destDir/$filename";

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    json_response(['ok' => false, 'msg' => 'File move failed'], 500);
}

try {
    $pdo = getPDO();
    $verStmt = $pdo->prepare("SELECT COALESCE(MAX(version),0) AS v FROM documents WHERE title=:title");
    $verStmt->execute([':title' => $title]);
    $ver = (int)$verStmt->fetchColumn() + 1;

    $relativePath = str_replace(realpath(__DIR__ . '/..') . '/', '', realpath($destPath));
    $stmt = $pdo->prepare("INSERT INTO documents 
        (title, filename, filepath, category, department, uploaded_by, version, created_at, updated_at)
        VALUES (:title, :filename, :filepath, :category, :department, :uploaded_by, :version, NOW(), NOW())");
    $stmt->execute([
        ':title' => $title,
        ':filename' => $filename,
        ':filepath' => $relativePath,
        ':category' => strtoupper($category),
        ':department' => strtoupper($department),
        ':uploaded_by' => $uploaded_by,
        ':version' => $ver
    ]);

    json_response(['ok' => true, 'msg' => 'File uploaded successfully']);
} catch (Exception $e) {
    @unlink($destPath);
    json_response(['ok' => false, 'msg' => 'Server error: ' . $e->getMessage()], 500);
}
