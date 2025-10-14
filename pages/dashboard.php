<?php
// pages/dashboard.php
session_start();
require_once __DIR__ . '/../php/db_connect.php'; // expects $pdo (PDO instance)

// current user name (safe)
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// helper attempts to fetch counts; tries multiple table names if needed
function fetchCount($pdo, array $queries) {
    foreach ($queries as $q) {
        try {
            $stmt = $pdo->query($q);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['c'])) return (int)$row['c'];
        } catch (PDOException $e) {
            // ignore and try next
        }
    }
    return 0;
}

// live counts (try typical names)
$total_documents   = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM documents", "SELECT COUNT(*) AS c FROM document"]);
$total_categories  = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM categories", "SELECT COUNT(*) AS c FROM document_categories"]);
$total_departments = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM departments"]);
$total_logs        = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM document_logs", "SELECT COUNT(*) AS c FROM logs"]);
$total_users       = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM users"]);
$recent_uploads    = fetchCount($pdo, ["SELECT COUNT(*) AS c FROM documents WHERE upload_date >= NOW() - INTERVAL 7 DAY", "SELECT COUNT(*) AS c FROM documents WHERE created_at >= NOW() - INTERVAL 7 DAY"]);

// sample storage values (use real query if you store sizes)
$storage_used_gb = 12.4;  // replace with real calculation if available
$storage_total_gb = 50;   // replace as needed
$storage_percent = round(($storage_used_gb / $storage_total_gb) * 100);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>DanteDocs — Dashboard</title>

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
    :root {
        --header-h: 64px;
        --sidebar-w: 260px;

        /* Light theme */
        --page-bg: #f5f7fb;
        --header-bg: #003366;
        --sidebar-bg-start: #e9f2ff;
        --sidebar-bg-end: #d6eaff;
        --card-bg: #ffffff;
        --muted: #6b7280;
        --accent: #007bff;
        --accent-2: #00bcd4;
        --footer-bg: #1f2937;
    }

    /* Dark theme */
    body.dark {
        --page-bg: #071022;
        --header-bg: #071425;
        --sidebar-bg-start: #071b2a;
        --sidebar-bg-end: #0b2434;
        --card-bg: #0f1724;
        --muted: #9aa7b2;
        --accent: #00bcd4;
        --accent-2: #26d7ff;
        --footer-bg: #071018;
    }

    html,
    body {
        height: 100%;
    }

    body {
        margin: 0;
        font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        background: var(--page-bg);
        color: var(--muted);
        transition: background .28s, color .28s;
        padding-bottom: 80px;
        /* space for footer */
    }

    /* TOPBAR */
    .topbar {
        height: var(--header-h);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--header-bg);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 18px;
        z-index: 1200;
        box-shadow: 0 4px 18px rgba(2, 6, 23, 0.15);
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }

    .brand .logo {
        font-size: 20px;
    }

    .topbar .controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* SIDEBAR */
    .sidebar {
        position: fixed;
        top: var(--header-h);
        left: 0;
        width: var(--sidebar-w);
        height: calc(100% - var(--header-h));
        padding: 16px 12px;
        background: linear-gradient(180deg, var(--sidebar-bg-start), var(--sidebar-bg-end));
        border-right: 1px solid rgba(0, 0, 0, 0.04);
        transition: transform .28s ease, box-shadow .28s ease;
        z-index: 1100;
        overflow-y: auto;
    }

    .sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0b2540;
        padding: 10px 12px;
        border-radius: 8px;
        margin: 6px 6px;
        font-weight: 600;
        text-decoration: none;
    }

    .sidebar .nav-link i {
        width: 22px;
        text-align: center;
    }

    .sidebar .nav-link:hover {
        background: rgba(13, 110, 253, 0.06);
        color: var(--accent);
        text-decoration: none;
    }

    .sidebar .nav-link.active {
        background: var(--accent);
        color: #fff;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    /* overlay for mobile */
    .overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        display: none;
        z-index: 1050;
        opacity: 0;
        transition: opacity .18s;
    }

    .overlay.show {
        display: block;
        opacity: 1;
    }

    /* APP CONTENT */
    .app {
        margin-top: var(--header-h);
        margin-left: var(--sidebar-w);
        padding: 18px;
        transition: margin-left .28s;
    }

    /* collapse behavior */
    .sidebar.hidden {
        transform: translateX(-110%);
    }

    .app.full {
        margin-left: 0;
    }

    /* Welcome banner */
    .welcome-banner {
        border-radius: 12px;
        padding: 14px 16px;
        color: #fff;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        transition: background .28s;
    }

    /* Stat card */
    .stat-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 6px 20px rgba(2, 6, 23, 0.06);
        transition: background .28s, color .28s;
    }

    .stat-card .icon {
        font-size: 26px;
        color: var(--accent);
    }

    body.dark .stat-card .icon {
        color: var(--accent-2);
    }

    .stat-value {
        font-weight: 700;
        font-size: 22px;
        color: #0b2540;
    }

    body.dark .stat-value {
        color: #e6f9ff;
    }

    /* Chartbox */
    .chartbox {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 8px 24px rgba(2, 6, 23, 0.06);
    }

    /* recent activity */
    .recent-table tbody tr td {
        vertical-align: middle;
    }

    /* footer */
    footer.app-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        height: 64px;
        background: var(--footer-bg);
        color: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1200;
        box-shadow: 0 -6px 18px rgba(2, 6, 23, 0.06);
    }

    /* responsive */
    @media (max-width: 991px) {
        .sidebar {
            transform: translateX(-110%);
        }

        .sidebar.open {
            transform: translateX(0);
            box-shadow: 8px 0 36px rgba(2, 6, 23, 0.25);
        }

        .app {
            margin-left: 0;
            padding: 14px;
        }

        .topbar .search {
            display: none;
        }

        .brand .full-title {
            display: none;
        }

        .hamburger {
            display: inline-flex;
        }
    }

    .hamburger {
        display: none;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 18px;
        margin-right: 6px;
    }

    .profile-btn {
        background: transparent;
        color: #fff;
        border: none;
    }
    </style>
</head>

<body>
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="d-flex align-items-center">
            <button class="hamburger me-2" id="btnHamb"><i class="fa-solid fa-bars"></i></button>
            <div class="brand">
                <div class="logo"><i class="fa-solid fa-file-invoice"></i></div>
                <div class="full-title">DanteDocs</div>
            </div>
        </div>

        <div class="d-flex align-items-center topbar-right">
            <div class="me-3 d-none d-md-block search">
                <input class="form-control form-control-sm" style="width:260px;" type="search"
                    placeholder="Search documents...">
            </div>

            <div class="me-2">
                <button id="themeToggle" class="btn btn-sm btn-outline-light" title="Toggle theme"><i id="themeIcon"
                        class="fa-solid fa-moon"></i></button>
            </div>

            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" class="text-white text-decoration-none">
                    <i class="fa-regular fa-user-circle fa-lg"></i>
                    &nbsp;<span><?php echo htmlspecialchars($user_name); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="px-2">
            <a class="nav-link active" href="#"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
            <a class="nav-link" href="#"><i class="fa-regular fa-file-lines"></i> Documents</a>
            <a class="nav-link" href="#"><i class="fa-regular fa-folder-open"></i> Categories</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-building"></i> Departments</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-users"></i> Users & Roles</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-clipboard-list"></i> Logs / Audit</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-cog"></i> Settings</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-chart-line"></i> Reports</a>
            <a class="nav-link" href="#"><i class="fa-solid fa-bell"></i> Notifications</a>
        </div>
    </nav>

    <!-- overlay -->
    <div id="overlay" class="overlay" aria-hidden="true"></div>

    <!-- APP CONTENT -->
    <main class="app" id="app">
        <div class="container-fluid">
            <!-- WELCOME -->
            <div class="welcome-banner">
                <div>
                    <div id="greeting" style="font-weight:700; font-size:1.05rem;">👋 Hello,
                        <?php echo htmlspecialchars($user_name); ?>!</div>
                    <div id="greetingSub" style="opacity:.9; font-size:.92rem;">Overview of activity and quick stats
                    </div>
                </div>
                <div style="text-align:right;">
                    <div id="clock" style="font-weight:700;"></div>
                    <small style="opacity:.85;">Local time</small>
                </div>
            </div>

            <!-- STAT CARDS -->
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-regular fa-file-lines icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Documents</div>
                                <div class="stat-value"><?php echo $total_documents; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-regular fa-folder-open icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Categories</div>
                                <div class="stat-value"><?php echo $total_categories; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-solid fa-building icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Departments</div>
                                <div class="stat-value"><?php echo $total_departments; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-solid fa-list-check icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Logs</div>
                                <div class="stat-value"><?php echo $total_logs; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-solid fa-users icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Users</div>
                                <div class="stat-value"><?php echo $total_users; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fa-solid fa-upload icon"></i></div>
                            <div>
                                <div class="small text-uppercase" style="opacity:.75">Recent uploads (7d)</div>
                                <div class="stat-value"><?php echo $recent_uploads; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STORAGE -->
            <div class="row g-3 mb-3">
                <div class="col-lg-8">
                    <div class="chartbox mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div><strong>Upload Trends</strong>
                                <div class="small text-muted">Last 30 days</div>
                            </div>
                            <div class="small text-muted">Dataset</div>
                        </div>
                        <canvas id="uploadChart" height="120"></canvas>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="chartbox mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><strong>Storage Usage</strong>
                                <div class="small text-muted">Used / Total</div>
                            </div>
                            <div class="small text-muted"><?php echo "{$storage_used_gb}GB / {$storage_total_gb}GB"; ?>
                            </div>
                        </div>

                        <div class="progress mt-3" style="height:18px;">
                            <div class="progress-bar" id="storageBar" role="progressbar"
                                style="width: <?php echo $storage_percent; ?>%;"
                                aria-valuenow="<?php echo $storage_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo $storage_percent; ?>%
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-2">Recent Activities</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2"><i class="fa-regular fa-file-lines me-2 text-primary"></i> Uploaded "Annual
                                Report 2025" — 2h ago</li>
                            <li class="mb-2"><i class="fa-regular fa-folder-open me-2 text-success"></i> Added
                                "Invoices" category — 5h ago</li>
                            <li class="mb-2"><i class="fa-regular fa-user me-2 text-muted"></i> Brenda logged in — today
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- RECENT ACTIVITY TABLE -->
            <div class="row g-3">
                <div class="col-12">
                    <div class="chartbox">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Recent Activity (logs)</strong>
                            <div class="small text-muted">Most recent 10</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover recent-table">
                                <thead>
                                    <tr class="text-muted">
                                        <th>#</th>
                                        <th>Document</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>When</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                // fetch up to 10 recent logs if table exists
                try {
                    $recent = $pdo->query("SELECT dl.id, COALESCE(d.title,dl.document_id) AS doc_title, COALESCE(u.name, dl.user_id) AS user_name, dl.action, dl.timestamp
                                            FROM document_logs dl
                                            LEFT JOIN documents d ON dl.document_id = d.id
                                            LEFT JOIN users u ON dl.user_id = u.id
                                            ORDER BY dl.timestamp DESC LIMIT 10");
                    $i = 1;
                    while ($row = $recent->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>" . htmlspecialchars($row['doc_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                        echo "<td>" . htmlspecialchars(date("d M Y H:i", strtotime($row['timestamp']))) . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    if ($i === 1) {
                        echo "<tr><td colspan='5' class='text-muted'>No recent activity found.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5' class='text-muted'>Recent logs not available.</td></tr>";
                }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="app-footer">
        © 2025 - DANTE SOFTWARES LTD. All Rights Reserved. Contact: dantechdevs@gmail.com
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
    // Elements
    const sidebar = document.getElementById('sidebar');
    const btnHamb = document.getElementById('btnHamb');
    const overlay = document.getElementById('overlay');
    const app = document.getElementById('app');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Sidebar open/close for mobile (slide-over)
    function openSidebar() {
        sidebar.classList.add('open');
        sidebar.classList.remove('hidden');
        overlay.classList.add('show');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        sidebar.classList.add('hidden');
        overlay.classList.remove('show');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    btnHamb.addEventListener('click', () => {
        // if already open, close
        if (sidebar.classList.contains('open')) closeSidebar();
        else openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => {
        // ensure overlay removed on resize to desktop
        if (window.innerWidth > 991) {
            overlay.classList.remove('show');
            overlay.style.display = 'none';
            sidebar.classList.remove('hidden');
            sidebar.classList.remove('open');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.add('hidden');
        }
    });

    // Theme persistent toggle
    function applyTheme(dark) {
        if (dark) {
            document.body.classList.add('dark');
            themeIcon.className = 'fa-solid fa-sun';
        } else {
            document.body.classList.remove('dark');
            themeIcon.className = 'fa-solid fa-moon';
        }
        // Update chart colors later via observer
    }

    // Init from storage
    const saved = localStorage.getItem('dante_theme');
    applyTheme(saved === 'dark');

    themeToggle.addEventListener('click', () => {
        const isDark = document.body.classList.contains('dark');
        applyTheme(!isDark);
        localStorage.setItem('dante_theme', !isDark ? 'dark' : 'light');
    });

    // greeting & clock
    function updateGreeting() {
        const now = new Date();
        const h = now.getHours();
        let greet = 'Hello';
        if (h >= 5 && h < 12) greet = 'Good morning';
        else if (h >= 12 && h < 17) greet = 'Good afternoon';
        else if (h >= 17 && h < 21) greet = 'Good evening';
        else greet = 'Good night';
        document.getElementById('greeting').innerHTML =
            `👋 ${greet}, <?php echo addslashes(htmlspecialchars($user_name)); ?>!`;
        document.getElementById('clock').innerText = now.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    updateGreeting();
    setInterval(updateGreeting, 30000);

    // Chart (Uploads per month sample)
    const ctx = document.getElementById('uploadChart').getContext('2d');

    function chartColors() {
        const isDark = document.body.classList.contains('dark');
        return {
            border: isDark ? '#00bcd4' : '#007bff',
            bg: isDark ? 'rgba(0,188,212,0.18)' : 'rgba(0,123,255,0.12)',
            ticks: isDark ? '#cfeef8' : '#1f2937'
        };
    }

    let uploadsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Uploads',
                data: [12, 18, 14, 22], // replace with real aggregated data if desired
                borderColor: chartColors().border,
                backgroundColor: chartColors().bg,
                fill: true,
                tension: 0.35,
                pointRadius: 3
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: chartColors().ticks
                    },
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        color: chartColors().ticks
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Keep chart colors in sync with theme changes
    const mo = new MutationObserver(() => {
        const colors = chartColors();
        uploadsChart.data.datasets[0].borderColor = colors.border;
        uploadsChart.data.datasets[0].backgroundColor = colors.bg;
        uploadsChart.options.scales.y.ticks.color = colors.ticks;
        uploadsChart.options.scales.x.ticks.color = colors.ticks;
        uploadsChart.update();
        // storage bar color adjust
        const storageBar = document.getElementById('storageBar');
        if (storageBar) storageBar.style.backgroundColor = document.body.classList.contains('dark') ?
            '#00bcd4' : '#007bff';
    });
    mo.observe(document.body, {
        attributes: true,
        attributeFilter: ['class']
    });

    // set initial storage bar color
    document.getElementById('storageBar').style.backgroundColor = document.body.classList.contains('dark') ? '#00bcd4' :
        '#007bff';
    </script>
</body>

</html>