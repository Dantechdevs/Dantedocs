<?php
// includes/sidebar.php
?>
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow-sm"
    style="min-height:100vh; background-color: #f8f9fa;">
    <div class="sidebar-sticky pt-4">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link active" href="../pages/dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link" href="../pages/manage_documents.php">
                    <i class="fas fa-file-alt"></i> Documents
                </a>
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

<style>
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
</style>