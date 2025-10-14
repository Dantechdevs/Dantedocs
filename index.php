<?php
// index.php
require_once __DIR__ . '/php/db_connect.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= APP_NAME ?> — Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h1>Welcome to <?= APP_NAME ?></h1>
        <p>This is the initial dashboard. Authentication will be added later.</p>
        <a href="pages/dashboard.php" class="btn btn-primary">Go to Dashboard</a>
    </div>
</body>

</html>