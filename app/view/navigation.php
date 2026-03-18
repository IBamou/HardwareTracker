<?php
$activePage = $_SERVER['REQUEST_URI']; 
?>

<!-- Bootstrap Navbar with Theme Matching Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .navbar-custom {
        background-color: #333 !important;
        border-bottom: 2px solid #ffd700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        padding: 0.8rem 1rem;
    }

    .navbar-custom .navbar-brand {
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .navbar-custom .nav-link {
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        margin: 0 0.2rem;
        transition: all 0.3s ease;
    }

    .navbar-custom .nav-link.active,
    .navbar-custom .nav-link:hover {
        color: #ffd700 !important;
        background-color: rgba(255,215,0,0.1);
    }

    .navbar-custom .navbar-toggler {
        border-color: rgba(255,255,255,0.3);
    }

    .navbar-custom .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar-custom .offcanvas {
        background-color: #333;
        color: white;
    }

    .navbar-custom .offcanvas-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .navbar-custom .offcanvas-title {
        color: white;
    }

    .navbar-custom .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid">
        <!-- Brand Name -->
        <a class="navbar-brand" href="/hardwareTracker/dashboard">
            <i class="bi bi-gear-wide-connected me-2"></i>
            Hardware Tracker
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Menu for Mobile -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Hardware Tracker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <!-- Home Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage == '/hardwareTracker/dashboard' ? 'active' : '' ?>" href="/hardwareTracker/dashboard">
                            <i class="bi bi-house-door me-1"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Categories Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage == '/hardwareTracker/hardwares' ? 'active' : '' ?>" href="/hardwareTracker/hardwares">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Hardwares
                        </a>
                    </li>
                    
                    <!-- Employees Link -->
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage == '/hardwareTracker/employees' ? 'active' : '' ?>" href="/hardwareTracker/employees">
                            <i class="bi bi-people me-1"></i> Employees
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>