<?php
include '../php/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dantedocs Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #e6f0fa;
            --header-bg: #003366;
            --footer-bg: #1e1e1e;
            --text-color: #222;
            --card-bg: #ffffff;
            --accent: #007bff;
            --hover-bg: #d0e3ff;
        }

        body.dark {
            --sidebar-bg: #1f2937;
            --header-bg: #111827;
            --footer-bg: #111827;
            --card-bg: #1e293b;
            --text-color: #e2e8f0;
            --hover-bg: #334155;
            --accent: #38bdf8;
            background: #0f172a;
        }

        body {
            margin: 0;
            background: #f8fafc;
            font-family: "Segoe UI", sans-serif;
            overflow-x: hidden;
            color: var(--text-color);
            transition: background 0.3s, color 0.3s;
        }

        /* HEADER */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--header-bg);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            transition: background 0.3s;
        }

        .header-title {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .hamburger {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 10px;
        }

        .theme-toggle {
            background: none;
            border: 1px solid #ccc;
            color: white;
            border-radius: 6px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100% - 60px);
            background: var(--sidebar-bg);
            border-right: 1px solid #d4e1f5;
            padding-top: 20px;
            transition: transform 0.3s ease, background 0.3s;
            z-index: 999;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: var(--hover-bg);
            color: var(--accent);
        }

        .sidebar a i {
            margin-right: 10px;
        }

        /* MAIN CONTENT */
        .content {
            margin-left: 250px;
            padding: 80px 20px 60px;
            transition: margin-left 0.3s;
        }

        .content.collapsed {
            margin-left: 0;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: var(--card-bg);
            transition: background 0.3s, color 0.3s;
        }

        /* FOOTER */
        footer {
            background: var(--footer-bg);
            color: #ddd;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding-top: 80px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="header-title">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <span><i class="fas fa-book"></i> Dantedocs</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="search" class="form-control d-none d-md-block" style="width: 220px;" placeholder="Search...">
            <button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i></button>
        </div>
    </header>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <a href="#" class="active"><i class="fas fa-file-alt"></i> Documents</a>
        <a href="#"><i class="fas fa-folder"></i> Categories</a>
        <a href="#"><i class="fas fa-clipboard-list"></i> Logs</a>
        <a href="#"><i class="fas fa-users"></i> Users</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="content" id="content">
        <div class="container-fluid">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                        <h6>Total Documents</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-folder-open fa-2x text-success mb-2"></i>
                        <h6>Categories</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-upload fa-2x text-info mb-2"></i>
                        <h6>New Uploads (7d)</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-list fa-2x text-warning mb-2"></i>
                        <h6>Activity Logs</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card p-3">
                        <h5><i class="fas fa-chart-line text-primary"></i> Document Upload Trends</h5>
                        <div style="height:250px;"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5><i class="fas fa-history text-teal"></i> Recent Activities</h5>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-file-alt text-primary"></i> Uploaded new document</li>
                            <li><i class="fas fa-folder text-success"></i> Added new category</li>
                            <li><i class="fas fa-user text-secondary"></i> User logged in</li>
                            <li><i class="fas fa-pen text-warning"></i> Updated file details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        © 2025 DANTE SOFTWARES LTD. All Rights Reserved.
    </footer>

    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const themeToggle = document.getElementById('themeToggle');
        const icon = themeToggle.querySelector('i');

        // Sidebar toggle for mobile
        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Dark mode toggle with memory
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const darkMode = document.body.classList.contains('dark');
            icon.className = darkMode ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('darkMode', darkMode ? 'enabled' : 'disabled');
        });

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark');
            icon.className = 'fas fa-sun';
        }
    </script>
</body>

</html>