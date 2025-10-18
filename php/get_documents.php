<?php
// php/get_documents.php
header('Content-Type: text/html; charset=utf-8');
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$now = time();
$sample = [
    [201, 'Staff Handbook.pdf', 'HR', 'Human Resources', 'Admin', date('c', $now - 3600 * 24 * 5), date('c', $now - 3600 * 4), 'v1.2'],
    [202, 'Finance Q3.xlsx', 'Finance', 'Finance Dept', 'Tina', date('c', $now - 3600 * 48), date('c', $now - 1800), 'v1.0'],
    [203, 'Procurement Policy.docx', 'Legal', 'Legal Dept', 'LawDept', date('c', $now - 3600 * 72), date('c', $now - 7200), 'v2.0'],
    [204, 'Project Plan - Alpha.pdf', 'Reports', 'PMO', 'ProjectLead', date('c', $now - 600), date('c', $now - 300), 'v0.9'],
    [205, 'Medical Guidelines.pdf', 'Education', 'Health Unit', 'Dr. O', date('c', $now - 3600 * 6), date('c', $now - 3600), 'v1.1'],
];

if ($search !== '') {
    $sample = array_filter($sample, function ($r) use ($search) {
        return stripos($r[1], $search) !== false || stripos($r[2], $search) !== false || stripos($r[3], $search) !== false;
    });
}

$html = '';
foreach ($sample as $r) {
    list($id, $title, $category, $department, $by, $uploaded_at, $updated_at, $version) = $r;
    $t = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $cat = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $dept = htmlspecialchars($department, ENT_QUOTES, 'UTF-8');
    $by = htmlspecialchars($by, ENT_QUOTES, 'UTF-8');
    $html .= "<tr data-id=\"{$id}\">";
    $html .= "<td>{$id}</td>";
    $html .= "<td>{$t}</td>";
    $html .= "<td class='col-cat'>{$cat}</td>";
    $html .= "<td class='col-dept'>{$dept}</td>";
    $html .= "<td>{$by}</td>";
    $html .= "<td data-updated=\"{$uploaded_at}\">" . date('Y-m-d H:i:s', strtotime($uploaded_at)) . "</td>";
    $html .= "<td data-updated=\"{$updated_at}\">" . date('Y-m-d H:i:s', strtotime($updated_at)) . "</td>";
    $html .= "<td>{$version}</td>";
    $html .= "<td>
    <button class='btn btn-sm btn-info' onclick=\"alert('View {$id}')\"><i class='fa-solid fa-eye'></i></button>
    <button class='btn btn-sm btn-outline-secondary' onclick=\"alert('Download {$id}')\"><i class='fa-solid fa-download'></i></button>
    <button class='btn btn-sm btn-outline-dark' onclick=\"alert('History {$id}')\"><i class='fa-solid fa-clock-rotate-left'></i></button>
  </td>";
    $html .= "</tr>\n";
}
echo $html;
