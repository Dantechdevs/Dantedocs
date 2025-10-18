<?php
// php/upload_document_backend.php
// Demo upload handler — minimal validation. Replace with secure logic (DB, auth, sanitization).

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}
if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
    http_response_code(400);
    echo "No file uploaded";
    exit;
}

$uploaddir = __DIR__ . '/../uploads/demo/';
if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);

$file = $_FILES['file'];
$fname = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
$target = $uploaddir . $fname;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    http_response_code(500);
    echo "Failed to save file";
    exit;
}

// You should add DB insert & logging here.
// For demo, respond success.
echo "File uploaded: {$fname}";
