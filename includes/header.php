<?php
// includes/header.php
?>
<header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm"
    style="background-color: #004d80 !important;">
    <a class="navbar-brand font-weight-bold ml-3" href="#">
        <i class="fas fa-book text-light"></i> Dantedocs
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop"
        aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTop">
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-3">
                <input type="text" class="form-control form-control-sm" placeholder="Search...">
            </li>

            <li class="nav-item mr-3">
                <button id="toggleDarkMode" class="btn btn-sm btn-outline-light" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Admin
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm">
                    <a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</header>

<script>
    document.getElementById("toggleDarkMode").addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
    });
</script>