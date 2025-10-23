<?php
// pages/documents.php
// DanteDocs — Documents (scanner/QR removed; organized layout + visible footer + dark mode)
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>DanteDocs — Documents</title>

    <!-- Bootstrap, FontAwesome, SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #004080;
            --muted: #6c757d;
            --card: #ffffff;
            --bg: #f5f7fb;
            --text: #1f2937;
            --topbar-text: #fff;
            --footer-bg: #f1f5f9;
        }

        /* Dark theme vars */
        body.dark {
            --primary: #0f1724;
            --muted: #9aa4b2;
            --card: #0b1220;
            --bg: #071025;
            --text: #e6eef9;
            --topbar-text: #fff;
            --footer-bg: #071025;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", Roboto, Arial;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: var(--primary);
            color: var(--topbar-text);
            display: flex;
            align-items: center;
            padding: 0 18px;
            z-index: 1200;
            box-shadow: 0 4px 18px rgba(2, 6, 23, 0.12);
        }

        .brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--topbar-text);
        }

        .topbar .controls {
            margin-left: auto;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* === Main content wrapper === */
        .app-wrapper {
            flex: 1;
            /* fills all space between topbar and footer */
            display: flex;
            justify-content: center;
            align-items: stretch;
            margin-top: 84px;
            /* leave space for topbar */
            width: 100%;
        }

        /* Layout inside wrapper */
        .layout {
            display: flex;
            gap: 20px;
            padding: 16px;
            max-width: 1260px;
            width: 100%;
            box-sizing: border-box;
            flex: 1;
        }

        /* Sidebar */
        .leftbar {
            width: 260px;
            position: sticky;
            top: 84px;
            height: calc(100vh - 84px);
            align-self: flex-start;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .leftcard {
            background: var(--card);
            padding: 14px;
            border-radius: 10px;
            box-shadow: 0 8px 26px rgba(2, 6, 23, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .leftcard .btn {
            width: 100%;
            text-align: left;
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 10px 12px;
        }

        /* Main content */
        .main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* Page header + cards */
        .page-header {
            background: var(--card);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 14px;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: var(--card);
            border-radius: 10px;
            border: none;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        /* Table styling */
        .table thead.table-primary th {
            background: var(--primary);
            color: #fff;
        }

        /* Small muted text */
        .small-muted {
            color: var(--muted);
            font-size: .92rem;
        }

        /* Footer (no white gap) */
        footer.site-footer {
            background: var(--footer-bg);
            color: var(--muted);
            padding: 18px;
            text-align: center;
            font-size: 0.92rem;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            margin-top: 0;
            /* remove any default spacing */
        }

        body.dark footer.site-footer {
            color: var(--text);
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        /* Buttons in leftcard: keep icons aligned */
        .leftcard .btn i {
            width: 20px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .layout {
                flex-direction: column;
                padding: 12px;
            }

            .leftbar {
                position: relative;
                width: 100%;
                top: 0;
                height: auto;
            }

            .topbar .controls .input-group {
                width: 200px !important;
            }
        }
    </style>

</head>

<body>

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="brand"><i class="fa-solid fa-folder-open"></i> DanteDocs</div>

        <div class="controls">
            <div class="input-group" style="width:400px;">
                <input id="globalSearch" type="search" class="form-control form-control-sm"
                    placeholder="Search documents (title, category, department)...">
                <button id="searchBtn" class="btn btn-sm btn-light"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>

            <button id="themeToggle" class="btn btn-sm btn-outline-light" title="Toggle theme"><i
                    class="fa-solid fa-moon"></i></button>

            <!-- Profile dropdown -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#"
                    data-bs-toggle="dropdown">
                    <img src="https://i.ibb.co/3zV9zYJ/avatar.png" alt="avatar" width="36" height="36"
                        class="rounded-circle me-2">
                    <small>Admin</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- APP WRAPPER (space reserved for topbar + footer) -->
    <div class="app-wrapper">
        <!-- PAGE LAYOUT -->
        <div class="layout">

            <!-- LEFT STICKY TOOLBAR -->
            <aside id="actionSidebar" class="leftbar shadow-sm">
                <div class="leftcard p-3 rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-primary fw-bold mb-0">
                            <i class="fa-solid fa-gears me-2"></i>Actions
                        </h6>
                        <button id="btnToggleActions" class="btn btn-sm btn-outline-secondary d-md-none">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="btnAddDocument" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-2"></i>Add Document
                        </button>
                        <button id="btnAddDept" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-building me-2"></i>Add Department
                        </button>
                        <button id="btnAddCat" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-tags me-2"></i>Add Category
                        </button>
                    </div>

                    <hr class="my-3">

                    <div class="d-grid gap-2">
                        <button id="btnImport" class="btn btn-info btn-sm text-white">
                            <i class="fa-solid fa-file-import me-2"></i>Import
                        </button>
                        <button id="btnExport" class="btn btn-dark btn-sm">
                            <i class="fa-solid fa-file-export me-2"></i>Export
                        </button>
                        <button id="btnPrint" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-print me-2"></i>Print
                        </button>
                    </div>

                    <hr class="my-3">

                    <div>
                        <h6 class="text-muted fw-bold small mb-2">
                            <i class="fa-solid fa-filter me-2"></i>Filters
                        </h6>
                        <select id="filterCategory" class="form-select form-select-sm mb-2">
                            <option value="">All Categories</option>
                        </select>
                        <select id="filterDepartment" class="form-select form-select-sm">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Floating toggle button (visible only on mobile) -->
            <button id="openActionsBtn" class="btn btn-primary btn-sm d-md-none floating-actions-btn">
                <i class="fa-solid fa-gears"></i>
            </button>


            <!-- ===== Sidebar Script ===== -->
            <script>
                const openActionsBtn = document.getElementById('openActionsBtn');
                const actionSidebar = document.getElementById('actionSidebar');
                const btnToggleActions = document.getElementById('btnToggleActions');

                // Open sidebar (mobile)
                openActionsBtn.addEventListener('click', () => {
                    actionSidebar.classList.add('active');
                });

                // Close sidebar (mobile)
                btnToggleActions.addEventListener('click', () => {
                    actionSidebar.classList.remove('active');
                });

                // Close sidebar when clicking outside
                document.addEventListener('click', (e) => {
                    if (
                        actionSidebar.classList.contains('active') &&
                        !actionSidebar.contains(e.target) &&
                        !openActionsBtn.contains(e.target)
                    ) {
                        actionSidebar.classList.remove('active');
                    }
                });
            </script>


            <!-- MAIN -->
            <main class="main">

                <!-- Page header -->
                <div class="page-header">
                    <div>
                        <h5 class="mb-0"><i class="fa-solid fa-file-lines text-primary me-2"></i> Document Management
                        </h5>
                        <div class="small-muted">Manage documents, categories, departments, uploads, and logs.</div>
                    </div>
                    <div class="text-muted small" id="clock">--</div>
                </div>

                <!-- Upload card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4"><input name="title" class="form-control"
                                        placeholder="Document Title" required></div>
                                <div class="col-md-2"><select name="category" id="uploadCategory" class="form-select"
                                        required>
                                        <option value="">Category</option>
                                    </select></div>
                                <div class="col-md-2"><select name="department" id="uploadDept" class="form-select"
                                        required>
                                        <option value="">Department</option>
                                    </select></div>
                                <div class="col-md-2"><input type="file" name="file" class="form-control" required>
                                </div>
                                <div class="col-md-2 text-end"><button class="btn btn-primary w-100" type="submit"><i
                                            class="fa-solid fa-upload me-1"></i> Upload</button></div>
                            </div>

                            <div id="uploadProgress" class="progress mt-3" style="display:none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width:0%">0%</div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Documents table -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>All Documents</strong>
                            <div class="small-muted">Auto-refresh: <span id="autoRefresh">ON</span></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Department</th>
                                        <th>Uploaded By</th>
                                        <th>Uploaded</th>
                                        <th>Updated</th>
                                        <th>Version</th>
                                        <th style="width:160px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="documentBody">
                                    <!-- rows loaded by AJAX from ../php/get_documents.php -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Document Logs (always visible) -->
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Document Logs</strong>
                            <div class="small-muted">Recent activity</div>
                        </div>
                        <div id="logsList" style="max-height:260px; overflow:auto;">
                            <div class="small text-muted">Loading logs…</div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="site-footer">
        © 2025 - DANTE SOFTWARES LTD. All Rights Reserved. Terms and Conditions apply.
    </footer>

    <!-- toasts wrapper -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // -----------------------
        // Utilities & helpers
        // -----------------------
        function toast(title, icon = 'success', timer = 2200) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: timer,
                timerProgressBar: true,
                title,
                icon
            });
        }

        function startClock() {
            const el = document.getElementById('clock');
            setInterval(() => el.textContent = new Date().toLocaleString(), 1000);
        }
        startClock();

        // theme toggle (light default) - improved to set body class and persist
        const themeToggle = document.getElementById('themeToggle');

        function applyTheme(t) {
            if (t === 'dark') document.body.classList.add('dark');
            else document.body.classList.remove('dark');
            const i = document.querySelector('#themeToggle i');
            i.className = document.body.classList.contains('dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
        themeToggle.addEventListener('click', () => {
            const isDark = !document.body.classList.contains('dark');
            applyTheme(isDark ? 'dark' : 'light');
            localStorage.setItem('dantedocs_theme', isDark ? 'dark' : 'light');
        });
        if (localStorage.getItem('dantedocs_theme') === 'dark') {
            applyTheme('dark');
        } else {
            applyTheme('light');
        }

        // -----------------------
        // Demo endpoints & data operations
        // -----------------------
        const REFRESH_MS = 30000;

        // friendly relative time
        function timeAgoIso(iso) {
            const diff = Math.floor((Date.now() - new Date(iso).getTime()) / 1000);
            if (diff < 5) return 'just now';
            if (diff < 60) return diff + 's ago';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return Math.floor(diff / 86400) + 'd ago';
        }

        // load documents and diff-update DOM
        function loadDocuments(search = '') {
            $.get('../php/get_documents.php', {
                search
            }, function(html) {
                // parse returned rows
                const tmp = document.createElement('tbody');
                tmp.innerHTML = html;
                const newRows = Array.from(tmp.querySelectorAll('tr'));
                const currentMap = {};
                document.querySelectorAll('#documentBody tr').forEach(r => currentMap[r.getAttribute('data-id')] =
                    r);

                newRows.forEach(row => {
                    const id = row.getAttribute('data-id');
                    // if server did not include data-updated attribute gracefully skip
                    const updatedNode = row.querySelector('[data-updated]');
                    const newUpdatedIso = updatedNode ? updatedNode.getAttribute('data-updated') : null;

                    if (!currentMap[id]) {
                        row.classList.add('flash-new');
                        document.getElementById('documentBody').insertBefore(row, document.getElementById(
                            'documentBody').firstChild);
                    } else {
                        const curUpdatedNode = currentMap[id].querySelector('[data-updated]');
                        const curUpdatedIso = curUpdatedNode ? curUpdatedNode.getAttribute('data-updated') :
                            null;
                        if (newUpdatedIso && curUpdatedIso !== newUpdatedIso) {
                            row.classList.add('flash-updated');
                            currentMap[id].replaceWith(row);
                        }
                    }
                });

                // remove deleted rows
                const serverIds = newRows.map(r => r.getAttribute('data-id'));
                document.querySelectorAll('#documentBody tr').forEach(r => {
                    if (!serverIds.includes(r.getAttribute('data-id'))) r.remove();
                });

                // update relative times
                document.querySelectorAll('[data-updated]').forEach(td => {
                    td.textContent = timeAgoIso(td.getAttribute('data-updated'));
                });

                // populate filters (category & department)
                const cats = new Set(),
                    depts = new Set();
                newRows.forEach(r => {
                    const c = (r.querySelector('.col-cat') || {
                        textContent: ''
                    }).textContent.trim();
                    const d = (r.querySelector('.col-dept') || {
                        textContent: ''
                    }).textContent.trim();
                    if (c) cats.add(c);
                    if (d) depts.add(d);
                });
                const catSel = document.getElementById('filterCategory'),
                    deptSel = document.getElementById('filterDepartment');
                if (catSel) {
                    catSel.innerHTML = '<option value="">All Categories</option>' + Array.from(cats).map(x =>
                        `<option>${x}</option>`).join('');
                }
                if (deptSel) {
                    deptSel.innerHTML = '<option value="">All Departments</option>' + Array.from(depts).map(x =>
                        `<option>${x}</option>`).join('');
                }

            }).fail(() => toast('Failed to load documents', 'error'));
        }

        // load logs (demo)
        function loadLogs() {
            // demo logs; replace with real endpoint get_logs.php for production
            const logs = [{
                    action: 'Uploaded',
                    doc: 'Project Plan - Alpha.pdf',
                    by: 'ProjectLead',
                    at: new Date(Date.now() - 5 * 60000).toISOString()
                },
                {
                    action: 'Updated',
                    doc: 'Finance Q3.xlsx',
                    by: 'Tina',
                    at: new Date(Date.now() - 30 * 60000).toISOString()
                },
                {
                    action: 'Uploaded',
                    doc: 'Medical Guidelines.pdf',
                    by: 'Dr. O',
                    at: new Date(Date.now() - 6 * 3600000).toISOString()
                },
            ];
            const html = logs.map(l =>
                `<div class="mb-2"><strong>${l.action}</strong> — ${l.doc} <div class="small-muted">${l.by} • ${timeAgoIso(l.at)}</div></div>`
            ).join('');
            document.getElementById('logsList').innerHTML = html;
        }

        // initial load + polling
        $(document).ready(function() {
            loadDocuments();
            loadLogs();
            setInterval(() => {
                loadDocuments($('#globalSearch').val().trim());
                loadLogs();
            }, REFRESH_MS);

            // search bindings
            $('#globalSearch').on('keyup', function() {
                loadDocuments($(this).val().trim());
            });
            $('#searchBtn').on('click', () => loadDocuments($('#globalSearch').val().trim()));
        });

        // -----------------------
        // Fill category/department selects for upload form (derived from get_documents demo)
        // -----------------------
        function fillCategoryDeptSelects() {
            $.get('../php/get_documents.php', function(html) {
                const tmp = document.createElement('tbody');
                tmp.innerHTML = html;
                const cats = new Set(),
                    depts = new Set();
                tmp.querySelectorAll('tr').forEach(r => {
                    const c = (r.querySelector('.col-cat') || {
                        textContent: ''
                    }).textContent.trim();
                    const d = (r.querySelector('.col-dept') || {
                        textContent: ''
                    }).textContent.trim();
                    if (c) cats.add(c);
                    if (d) depts.add(d);
                });
                const catHtml = '<option value="">Category</option>' + Array.from(cats).map(x =>
                    `<option>${x}</option>`).join('');
                const deptHtml = '<option value="">Department</option>' + Array.from(depts).map(x =>
                    `<option>${x}</option>`).join('');
                document.querySelectorAll('#uploadCategory, select[name="category"]').forEach(s => s.innerHTML =
                    catHtml);
                document.querySelectorAll('#uploadDept, select[name="department"]').forEach(s => s.innerHTML =
                    deptHtml);
                document.getElementById('filterCategory').innerHTML = '<option value="">All Categories</option>' +
                    Array.from(cats).map(x => `<option>${x}</option>`).join('');
                document.getElementById('filterDepartment').innerHTML =
                    '<option value="">All Departments</option>' + Array.from(depts).map(x =>
                        `<option>${x}</option>`).join('');
            }).fail(() => {
                /* ignore */
            });
        }
        // call once after doc load
        $(document).on('ready', fillCategoryDeptSelects);
        setTimeout(fillCategoryDeptSelects, 800);

        // -----------------------
        // Upload handler (AJAX)
        // -----------------------
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $('#uploadProgress').show();
            $('.progress-bar').css('width', '0%').text('0%');
            $.ajax({
                url: '../php/upload_document_backend.php',
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', (ev) => {
                        if (ev.lengthComputable) {
                            const p = Math.round((ev.loaded / ev.total) * 100);
                            $('.progress-bar').css('width', p + '%').text(p + '%');
                        }
                    });
                    return xhr;
                },
                success: function(res) {
                    $('#uploadProgress').hide();
                    toast('Document uploaded', 'success');
                    $('#uploadForm')[0].reset();
                    loadDocuments();
                    loadLogs();
                    fillCategoryDeptSelects();
                },
                error: function() {
                    $('#uploadProgress').hide();
                    toast('Upload failed', 'error');
                }
            });
        });
        // -----------------------
        // Add category / Add department (with backend + dropdown refresh)
        // -----------------------
        function promptAndPost(title, endpoint, type) {
            Swal.fire({
                title,
                input: 'text',
                inputPlaceholder: 'Enter ' + type + ' name',
                showCancelButton: true,
                confirmButtonText: 'Save'
            }).then(result => {
                if (result.isConfirmed && result.value.trim() !== '') {
                    $.post(endpoint, {
                        name: result.value.trim()
                    }, function(resp) {
                        try {
                            const data = JSON.parse(resp);
                            if (data.success) {
                                toast(type + ' added successfully', 'success');
                                fillCategoryDeptSelects(); // reload dropdowns
                                loadDocuments(); // refresh document list
                            } else {
                                toast(data.message || 'Error saving ' + type, 'error');
                            }
                        } catch (e) {
                            toast('Server error: invalid response', 'error');
                            console.error(resp);
                        }
                    }).fail(() => toast('Request failed', 'error'));
                }
            });
        }

        // Button bindings
        $('#btnAddCat').on('click', () => promptAndPost('Add New Category', '../php/add_category.php', 'Category'));
        $('#btnAddDept').on('click', () => promptAndPost('Add New Department', '../php/add_department.php', 'Department'));


        <
        /body>

        <
        /html>