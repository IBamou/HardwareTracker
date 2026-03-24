<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/hardware/hardwareDashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        body {
            background: #f8f5f0 !important; /* Warm cream background */
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #333; /* Match nav dark brown text */
            /* padding-top: 1rem; */
        }

        /* Enhance nav styling to match theme */
        nav {
            background: #333 !important;
            border-bottom: 2px solid #ffd700; /* Yellow accent from active links */
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        nav a {
            transition: color 0.3s ease ;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
        }

        nav a:hover {
            color: #ffd700 !important;
            background: rgba(255,215,0,0.1);
        }

    </style>
</head>
<body >


    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 ms-sm-auto px-4" style="margin:auto;">
                <!-- Dashboard Header -->
                <div class="dashboard-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1>Hardware Dashboard</h1>
                            <p class="text-muted">Overview of your hardware inventory</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card card-total">
                            <i class="bi bi-pc-display"></i>
                            <h3><?php echo $totalHardware; ?></h3>
                            <p>Total Devices</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card card-assigned">
                            <i class="bi bi-person-check"></i>
                            <h3><?php echo $assignedHardware; ?></h3>
                            <p>Assigned Devices</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card card-unassigned">
                            <i class="bi bi-person-x"></i>
                            <h3><?php echo $unassignedHardware; ?></h3>
                            <p>Unassigned Devices</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card card-value">
                            <i class="bi bi-cash-stack"></i>
                            <div class="price-display">$<?php echo number_format($totalPrice, 2); ?></div>
                            <p>Total Inventory Value</p>
                        </div>
                    </div>
                </div>
<div class="container mt-4">

    <!-- 🔥 Tabs -->
    <div class="d-flex gap-2 mb-3">
        <form action="dashboard" method="GET">
            <button class="btn btn-outline-warning"  type="submit" name="type" value="hardwares">Hardwares</button>
        </form>
        <form action="dashboard" method="GET">
            <button class="btn btn-outline-warning"  type="submit" name="type" value="employees" >Employees</button>
        </form>
         <!-- <form action="dashboard" method="GET">
              <button class="btn btn-outline-warning" type="submit" name="type" value="hardwareCategories">Hardware Categories</button>
        </form>
         <form action="dashboard" method="GET">
              <button class="btn btn-outline-warning" type="submit" name="type" value="employeeCategories">Employee Categories</button>
        </form> -->
    </div>

    <!-- 🔍 Search -->
    <form method="GET" action="dashboard" id="searchForm" class="d-flex ms-auto form-control me-2 rounded-pill shadow-sm border-0 bg-light">
        <input type="hidden" name="type" id="typeInput" value="<?= $type ?? 'hardwares'?>">
        <input 
            type="text" 
            name="search" 
            id="searchInput"
            class="form-control d-flex ms-auto form-control me-2 rounded-pill shadow-sm border-0 bg-light" 
            placeholder="<?=$placeholders[$type]?>"
        >
        <button class="btn btn-outline-warning rounded-pill" type="submit">Search</button>
    </form>

    <?php if (isset($page)) : ?>
        <?php include_once $page?>
    <?php endif; ?>
</div>
</body>
</html>