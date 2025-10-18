<?php
// includes/sidebar.php
?>
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm" style="min-height:100vh;">
    <div class="sidebar-sticky pt-4">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link active" href="pages/dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/documents.php">
                    <i class="fas fa-file-alt"></i> Documents
                </a>
            </li>

            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/document_categories.php">
                    <i class="fas fa-folder-open"></i> Categories
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/document_logs.php">
                    <i class="fas fa-clipboard-list"></i> Logs
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- 🌙 Theme Toggle Button -->
<button id="themeToggle" class="btn btn-sm btn-outline-secondary"
    style="position: fixed; top: 15px; right: 15px; z-index: 999;">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
</button>

<style>
    /* =====================
   🌞 LIGHT MODE DEFAULT
   ===================== */
    #sidebar {
        background-color: #f8f9fa;
        color: #004d80;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav-link {
        color: #004d80 !important;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .nav-link:hover {
        color: #007bff !important;
        background-color: #e9f3ff;
        border-radius: 8px;
    }

    .nav-link.active {
        color: white !important;
        background-color: #007bff;
        border-radius: 8px;
    }

    /* =====================
   🌙 DARK MODE (linked with themeUtils)
   ===================== */
    body.dark {
        background-color: #1359fdff;
        color: #070707ff;
    }

    body.dark #sidebar {
        background-color: #111827 !important;
        color: #111112ff !important;
    }

    body.dark .nav-link {
        color: #cbd5e1 !important;
    }

    body.dark .nav-link:hover {
        color: #ffffff !important;
        background-color: #1e293b !important;
        border-radius: 8px;
    }

    body.dark .nav-link.active {
        background-color: #38bdf8 !important;
        color: #ffffff !important;
    }

    /* Optional icon spacing */
    .nav-link i {
        margin-right: 8px;
    }
</style>

<script>
    // 🔆 Use the same logic as themeUtils (for global consistency)
    const themeIcon = document.getElementById('themeIcon');
    const themeToggle = document.getElementById('themeToggle');

    const themeUtils = {
        isDark: localStorage.getItem('dante_theme') === 'dark',
        apply(dark) {
            document.body.classList.toggle('dark', dark);
            themeIcon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            localStorage.setItem('dante_theme', dark ? 'dark' : 'light');
            this.isDark = dark;
        },
        toggle() {
            this.apply(!this.isDark);
        }
    };

    // Event listener for toggle
    themeToggle.addEventListener('click', () => themeUtils.toggle());

    // Apply theme on page load
    document.addEventListener('DOMContentLoaded', () => {
        themeUtils.apply(themeUtils.isDark);
    });
</script>