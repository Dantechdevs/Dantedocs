<?php
// Demo: handle add category
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}
$name = trim($_POST['name'] ?? '');
if ($name === '') {
    http_response_code(400);
    echo "Category name required";
    exit;
}
// In production: insert into categories table.
// Demo: just return success.
echo "Category '{$name}' added (demo)";
