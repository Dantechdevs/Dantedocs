<?php
// Demo: handle add department
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}
$name = trim($_POST['name'] ?? '');
if ($name === '') {
    http_response_code(400);
    echo "Department name required";
    exit;
}
// In production: insert into departments table.
// Demo: just return success.
echo "Department '{$name}' added (demo)";
