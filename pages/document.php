<?php
include('includes/header.php');
include('php/session_check.php');
checkSession();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>

    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/document.css">

    <!-- FontAwesome + Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="<?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'dark' : ''; ?>">

    <!-- Topbar -->
    <div class="topbar">
        <div class="brand">
            <i class="fa-solid fa-folder-open"></i> <span>Document Center</span>
        </div>
        <div class="controls">
            <button id="themeToggle" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-moon"></i></button>
        </div>
    </div>

    <!-- App Wrapper -->
    <div class="app-container container-fluid mt-5 pt-4">
        <!-- Upload Form -->
        <div class="upload-section card shadow-sm mb-4">
            <div class="card-body">
                <h5><i class="fa-solid fa-cloud-arrow-up"></i> Upload New Document</h5>
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <input type="text" name="title" class="form-control" placeholder="Document Title" required>
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Reports">Reports</option>
                                <option value="Finance">Finance</option>
                                <option value="Legal">Legal</option>
                                <option value="HR">HR</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload</button>
                    </div>
                </form>
                <div id="uploadProgress" class="progress mt-3" style="display:none;">
                    <div class="progress-bar" role="progressbar" style="width:0%">0%</div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="fa-solid fa-folder-tree"></i> Documents List</h5>
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search documents...">
        </div>

        <!-- Document Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover align-middle" id="documentTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Version</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="documentBody">
                        <!-- Fetched dynamically via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer text-center mt-5 py-3">
        <small>© <?= date('Y'); ?> Dante Document System | All Rights Reserved.</small>
    </footer>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // === Dark Mode Toggle ===
        const toggle = document.getElementById('themeToggle');
        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const icon = toggle.querySelector('i');
            icon.className = document.body.classList.contains('dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            document.cookie = "theme=" + (document.body.classList.contains('dark') ? 'dark' : 'light');
        });

        // === AJAX Upload ===
        $("#uploadForm").on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $("#uploadProgress").show();
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            var percent = Math.round((e.loaded / e.total) * 100);
                            $(".progress-bar").width(percent + "%").text(percent + "%");
                        }
                    });
                    return xhr;
                },
                type: 'POST',
                url: 'php/upload_document_backend.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response);
                    $("#uploadForm")[0].reset();
                    $("#uploadProgress").hide();
                    loadDocuments();
                }
            });
        });

        // === Load Documents ===
        function loadDocuments(query = '') {
            $.ajax({
                url: 'php/get_documents.php',
                method: 'GET',
                data: {
                    search: query
                },
                success: function(data) {
                    $("#documentBody").html(data);
                }
            });
        }

        // === Live Search ===
        $("#searchInput").on("keyup", function() {
            loadDocuments($(this).val());
        });

        // Initialize
        $(document).ready(() => loadDocuments());
    </script>
</body>

</html>