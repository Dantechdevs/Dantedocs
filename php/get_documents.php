<?php
// php/get_documents.php
require_once __DIR__ . '/db.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = max(5, (int)($_GET['limit'] ?? 10));
$offset = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$format = strtolower(trim($_GET['format'] ?? 'html')); // html | json

try {
    $pdo = getPDO();

    // Count total
    $countSql = "SELECT COUNT(*) FROM documents WHERE 1=1";
    $sql = "SELECT * FROM documents WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $filter = " AND (title LIKE :s OR category LIKE :s OR department LIKE :s)";
        $countSql .= $filter;
        $sql .= $filter;
        $params[':s'] = "%$search%";
    }

    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    $sql .= " ORDER BY created_at DESC LIMIT :offset, :limit";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    if ($format === 'json') {
        json_response([
            'ok' => true,
            'data' => $rows,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    // HTML output (for your current front-end table)
    if (!$rows) {
        echo "<tr><td colspan='9' class='text-center text-muted'>No records found</td></tr>";
        exit;
    }

    foreach ($rows as $i => $r) {
        $id = htmlspecialchars($r['id']);
        $title = htmlspecialchars($r['title']);
        $cat = htmlspecialchars($r['category']);
        $dept = htmlspecialchars($r['department']);
        $uploaded_by = htmlspecialchars($r['uploaded_by']);
        $created = htmlspecialchars($r['created_at']);
        $updated = htmlspecialchars($r['updated_at']);
        $version = htmlspecialchars($r['version']);
        $filepath = htmlspecialchars($r['filepath']);
        $downloadUrl = '../' . str_replace('\\', '/', $filepath);

        echo "<tr data-id='{$id}'>
            <td>" . ($offset + $i + 1) . "</td>
            <td><strong>{$title}</strong></td>
            <td>{$cat}</td>
            <td>{$dept}</td>
            <td>{$uploaded_by}</td>
            <td>" . date('Y-m-d H:i', strtotime($created)) . "</td>
            <td>" . date('Y-m-d H:i', strtotime($updated)) . "</td>
            <td>{$version}</td>
            <td>
                <a class='btn btn-sm btn-outline-primary me-1' href='{$downloadUrl}' download><i class='fa fa-download'></i></a>
                <button class='btn btn-sm btn-outline-danger delete-btn' data-id='{$id}'><i class='fa fa-trash'></i></button>
            </td>
        </tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='9' class='text-danger'>Server error: {$e->getMessage()}</td></tr>";
}
