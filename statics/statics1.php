<?php
// hardwares.php
session_start();
require_once 'db.php'; // Your database connection

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query with search
$pdo = $db;
$query = "SELECT h.*, 
                 c.name as category_name,
                 ec.name as employee_category_name,
                 e.first_name,
                 e.last_name,
                 e.email,
                 e.departement,
                 a.assigned_at,
                 a.returned_at
          FROM hardwares h
          LEFT JOIN categories c ON h.category_id = c.id
          LEFT JOIN assignments a ON h.id = a.hardware_id AND a.returned_at IS NULL
          LEFT JOIN employees e ON a.employee_id = e.id
          LEFT JOIN employee_categories ec ON e.category_id = ec.id
          WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (h.name LIKE :search OR h.serial_number LIKE :search 
                OR e.first_name LIKE :search OR e.last_name LIKE :search)";
}

$query .= " ORDER BY h.created_at DESC";

$stmt = $pdo->prepare($query);

if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
}

$stmt->execute();
$hardwares = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$totalHardware = $pdo->query("SELECT COUNT(*) FROM hardwares")->fetchColumn();
$assignedHardware = $pdo->query("SELECT COUNT(DISTINCT hardware_id) FROM assignments WHERE returned_at IS NULL")->fetchColumn();
$unassignedHardware = $totalHardware - $assignedHardware;
$categoriesCount = $pdo->query("SELECT COUNT(DISTINCT category_id) FROM hardwares")->fetchColumn();

// Calculate total prices
$totalPriceQuery = "SELECT 
    SUM(price) as total_price,
    SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as available_price,
    SUM(CASE WHEN status = 'in_use' THEN price ELSE 0 END) as in_use_price,
    SUM(CASE WHEN status = 'maintenance' THEN price ELSE 0 END) as maintenance_price
    FROM hardwares";
$priceStats = $pdo->query($totalPriceQuery)->fetch(PDO::FETCH_ASSOC);

$totalPrice = $priceStats['total_price'] ?? 0;
$availablePrice = $priceStats['available_price'] ?? 0;
$inUsePrice = $priceStats['in_use_price'] ?? 0;
$maintenancePrice = $priceStats['maintenance_price'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Inventory</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --primary-dark: #333;
            --accent-gold: #ffd700;
            --accent-brown: #8b4513;
            --accent-light-brown: #a0522d;
            --background-light: #f8f5f0;
            --card-white: white;
            --border-light: #e8e0d5;
            --text-dark: #333;
            --text-light: #666;
            --success-green: #28a745;
            --info-blue: #17a2b8;
            --warning-orange: #ffc107;
            --danger-red: #dc3545;
        }
        
        body {
            background: var(--background-light);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-bottom: 2rem;
        }
        
        /* Navigation */
        .navbar {
            background: var(--primary-dark) !important;
            border-bottom: 2px solid var(--accent-gold);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
        
        .nav-link {
            transition: all 0.3s ease;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
            margin: 0 0.2rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--accent-gold) !important;
            background: rgba(255,215,0,0.1);
        }
        
        /* Page Header */
        .page-header {
            text-align: center;
            margin: 2rem 0 2rem;
        }
        
        .page-title {
            color: var(--text-dark);
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }
        
        .page-title i {
            color: var(--accent-brown);
        }
        
        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid var(--accent-brown);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card h6 {
            color: var(--text-light);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .stats-card h2 {
            color: var(--accent-brown);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        .stats-card small {
            color: var(--text-light);
            font-size: 0.85rem;
        }
        
        .price-stats {
            font-size: 0.9rem;
            color: var(--success-green);
            font-weight: 600;
        }
        
        /* Main Card */
        .main-card {
            background: var(--card-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-brown);
        }
        
        /* Search Bar */
        .search-container {
            background: #faf8f5;
            border: 2px solid var(--border-light);
            border-radius: 10px;
            padding: 0.5rem;
        }
        
        .search-container .input-group-text {
            background: transparent;
            border: none;
            color: var(--accent-brown);
        }
        
        .search-container .form-control {
            border: none;
            background: transparent;
            font-family: 'Inter', sans-serif;
            padding: 0.5rem;
        }
        
        .search-container .form-control:focus {
            box-shadow: none;
            outline: none;
        }
        
        .btn-search {
            background: var(--accent-brown);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-search:hover {
            background: #654321;
            transform: translateY(-2px);
        }
        
        .btn-clear {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-clear:hover {
            background: var(--border-light);
            color: var(--text-dark);
        }
        
        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: var(--primary-dark);
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid var(--border-light);
        }
        
        .table tbody tr:hover {
            background-color: rgba(139, 69, 19, 0.05);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        /* Badges */
        .category-badge {
            background: rgba(139, 69, 19, 0.1);
            color: var(--accent-brown);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .employee-badge {
            background: rgba(255, 215, 0, 0.1);
            color: #b8860b;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        .price-badge {
            background: rgba(34, 139, 34, 0.1);
            color: #228b22;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-available { background: rgba(34, 139, 34, 0.1); color: #228b22; }
        .status-in_use { background: rgba(30, 144, 255, 0.1); color: #1e90ff; }
        .status-maintenance { background: rgba(255, 165, 0, 0.1); color: #ff8c00; }
        .status-retired { background: rgba(128, 128, 128, 0.1); color: #666; }
        .status-lost { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
        }
        
        .btn-view {
            background: rgba(30, 144, 255, 0.1);
            color: #1e90ff;
        }
        
        .btn-view:hover {
            background: #1e90ff;
            color: white;
        }
        
        .btn-edit {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .btn-edit:hover {
            background: #ffc107;
            color: white;
        }
        
        .btn-assign {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .btn-assign:hover {
            background: #28a745;
            color: white;
        }
        
        .btn-unassign {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .btn-unassign:hover {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
        }
        
        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }
        
        /* Modal Styling */
        .modal-header {
            background: var(--primary-dark);
            color: white;
            border-bottom: 2px solid var(--accent-gold);
            padding: 1rem 1.5rem;
        }
        
        .modal-title i {
            color: var(--accent-gold);
            margin-right: 0.5rem;
        }
        
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid var(--border-light);
            padding: 1rem 1.5rem;
        }
        
        .btn-modal {
            background: var(--accent-brown);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-modal:hover {
            background: #654321;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--border-light);
            margin-bottom: 1.5rem;
        }
        
        .empty-state h4 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        
        .btn-add {
            background: var(--accent-brown);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .btn-add:hover {
            background: #654321;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
            color: white;
        }
        
        /* Total Price Summary */
        .price-summary {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--success-green);
        }
        
        .price-summary h5 {
            color: var(--accent-brown);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .price-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .price-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid var(--accent-brown);
        }
        
        .price-item-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }
        
        .price-item-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent-brown);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-card {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
            
            .price-summary-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* DataTables Customization */
        .dataTables_wrapper {
            margin-top: 1rem;
        }
        
        .dataTables_length select,
        .dataTables_filter input {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
        }
        
        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            border-color: var(--accent-brown);
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .dataTables_paginate .paginate_button {
            border: 1px solid var(--border-light) !important;
            border-radius: 6px !important;
            margin: 0 2px;
        }
        
        .dataTables_paginate .paginate_button.current {
            background: var(--accent-brown) !important;
            color: white !important;
            border-color: var(--accent-brown) !important;
        }
        
        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-pc-display"></i> Hardware Inventory
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="hardwares.php">
                            <i class="bi bi-list-ul"></i> All Devices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_hardware.php">
                            <i class="bi bi-plus-circle"></i> Add Hardware
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="employees.php">
                            <i class="bi bi-people"></i> Employees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">
                            <i class="bi bi-tags"></i> Categories
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-pc-display-horizontal"></i>
                Hardware Inventory
            </h1>
            <p class="text-muted">Manage all hardware devices and assignments</p>
        </div>

        <!-- Total Price Summary -->
        <div class="price-summary">
            <h5><i class="bi bi-cash-stack"></i> Total Inventory Value</h5>
            <div class="price-summary-grid">
                <div class="price-item">
                    <div class="price-item-label">Total Value</div>
                    <div class="price-item-value">$<?php echo number_format($totalPrice, 2); ?></div>
                </div>
                <div class="price-item">
                    <div class="price-item-label">Available Devices</div>
                    <div class="price-item-value">$<?php echo number_format($availablePrice, 2); ?></div>
                </div>
                <div class="price-item">
                    <div class="price-item-label">In Use Devices</div>
                    <div class="price-item-value">$<?php echo number_format($inUsePrice, 2); ?></div>
                </div>
                <div class="price-item">
                    <div class="price-item-label">Maintenance Devices</div>
                    <div class="price-item-value">$<?php echo number_format($maintenancePrice, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h6>Total Devices</h6>
                    <h2><?php echo $totalHardware; ?></h2>
                    <small>All hardware items</small>
                    <?php if($totalPrice > 0): ?>
                        <div class="price-stats mt-2">
                            <i class="bi bi-currency-dollar"></i>
                            Total: $<?php echo number_format($totalPrice, 2); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h6>Assigned</h6>
                    <h2><?php echo $assignedHardware; ?></h2>
                    <small>Currently in use</small>
                    <?php if($inUsePrice > 0): ?>
                        <div class="price-stats mt-2">
                            <i class="bi bi-currency-dollar"></i>
                            Value: $<?php echo number_format($inUsePrice, 2); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h6>Unassigned</h6>
                    <h2><?php echo $unassignedHardware; ?></h2>
                    <small>Available for assignment</small>
                    <?php if($availablePrice > 0): ?>
                        <div class="price-stats mt-2">
                            <i class="bi bi-currency-dollar"></i>
                            Value: $<?php echo number_format($availablePrice, 2); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h6>Categories</h6>
                    <h2><?php echo $categoriesCount; ?></h2>
                    <small>Hardware categories</small>
                </div>
            </div>
        </div>

        <!-- Search Card -->
        <div class="main-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="mb-0">
                        <i class="bi bi-search" style="color: var(--accent-brown);"></i>
                        Search Devices
                    </h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="search-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Search hardware, serial, or employee..."
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn-search" type="submit">
                                Search
                            </button>
                            <?php if(!empty($search)): ?>
                                <a href="hardwares.php" class="btn-clear ms-2">
                                    Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hardware Table -->
        <div class="main-card">
            <?php if(count($hardwares) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="hardwareTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Device Name</th>
                                <th>Serial Number</th>
                                <th>Category</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($hardwares as $hardware): 
                                $isAssigned = !empty($hardware['first_name']);
                                $status = $hardware['status'] ?? 'available';
                                $statusClass = 'status-' . $status;
                            ?>
                                <tr>
                                    <td>#<?php echo str_pad($hardware['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($hardware['name']); ?></strong>
                                        <?php if($hardware['purchase_date']): ?>
                                            <br><small class="text-muted">
                                                Purchased: <?php echo date('M d, Y', strtotime($hardware['purchase_date'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($hardware['serial_number']): ?>
                                            <code><?php echo htmlspecialchars($hardware['serial_number']); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($hardware['category_name']): ?>
                                            <span class="category-badge">
                                                <?php echo htmlspecialchars($hardware['category_name']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="category-badge">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($isAssigned): ?>
                                            <div class="employee-badge">
                                                <i class="bi bi-person-circle"></i>
                                                <?php echo htmlspecialchars($hardware['first_name'] . ' ' . $hardware['last_name']); ?>
                                                <?php if($hardware['departement']): ?>
                                                    <br><small class="text-muted">
                                                        <?php echo htmlspecialchars($hardware['departement']); ?>
                                                        <?php if($hardware['employee_category_name']): ?>
                                                            • <?php echo htmlspecialchars($hardware['employee_category_name']); ?>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($hardware['price'] && $hardware['price'] > 0): ?>
                                            <span class="price-badge">
                                                $<?php echo number_format($hardware['price'], 2); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- View Details Button -->
                                            <button type="button" 
                                                    class="btn btn-view" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal<?php echo $hardware['id']; ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <a href="edit_hardware.php?id=<?php echo $hardware['id']; ?>" 
                                               class="btn btn-edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <!-- Assign/Unassign Button -->
                                            <button type="button" 
                                                    class="btn <?php echo $isAssigned ? 'btn-unassign' : 'btn-assign'; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#assignModal<?php echo $hardware['id']; ?>">
                                                <i class="bi bi-<?php echo $isAssigned ? 'person-dash' : 'person-plus'; ?>"></i>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button type="button" 
                                                    class="btn btn-delete"
                                                    onclick="confirmDelete(<?php echo $hardware['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Details Modal -->
                                <div class="modal fade" id="detailsModal<?php echo $hardware['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-info-circle"></i> 
                                                    <?php echo htmlspecialchars($hardware['name']); ?> Details
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="mb-3" style="color: var(--accent-brown);">
                                                            <i class="bi bi-info-square"></i> Basic Information
                                                        </h6>
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <th width="40%">ID:</th>
                                                                <td>#<?php echo str_pad($hardware['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Name:</th>
                                                                <td><?php echo htmlspecialchars($hardware['name']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Serial Number:</th>
                                                                <td>
                                                                    <?php if($hardware['serial_number']): ?>
                                                                        <code><?php echo htmlspecialchars($hardware['serial_number']); ?></code>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">N/A</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Category:</th>
                                                                <td>
                                                                    <?php if($hardware['category_name']): ?>
                                                                        <span class="category-badge">
                                                                            <?php echo htmlspecialchars($hardware['category_name']); ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="category-badge">Uncategorized</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td>
                                                                    <span class="status-badge <?php echo $statusClass; ?>">
                                                                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="mb-3" style="color: var(--accent-brown);">
                                                            <i class="bi bi-calendar-check"></i> Dates & Price
                                                        </h6>
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <th width="40%">Price:</th>
                                                                <td>
                                                                    <?php if($hardware['price'] && $hardware['price'] > 0): ?>
                                                                        <strong class="price-badge">
                                                                            $<?php echo number_format($hardware['price'], 2); ?>
                                                                        </strong>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">Not set</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Purchase Date:</th>
                                                                <td>
                                                                    <?php if($hardware['purchase_date']): ?>
                                                                        <?php echo date('M d, Y', strtotime($hardware['purchase_date'])); ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">N/A</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Received Date:</th>
                                                                <td>
                                                                    <?php if($hardware['received_date']): ?>
                                                                        <?php echo date('M d, Y', strtotime($hardware['received_date'])); ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">N/A</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Created:</th>
                                                                <td><?php echo date('M d, Y H:i', strtotime($hardware['created_at'])); ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                                <!-- Assignment History -->
                                                <div class="mt-4">
                                                    <h6 class="                                                    <h6 class="mb-3" style="color: var(--accent-brown);">
                                                        <i class="bi bi-clock-history"></i> Assignment History
                                                    </h6>
                                                    <?php
                                                    $assignmentHistory = $pdo->prepare("
                                                        SELECT a.*, e.first_name, e.last_name, e.departement, e.email
                                                        FROM assignments a
                                                        JOIN employees e ON a.employee_id = e.id
                                                        WHERE a.hardware_id = ?
                                                        ORDER BY a.assigned_at DESC
                                                    ");
                                                    $assignmentHistory->execute([$hardware['id']]);
                                                    $assignments = $assignmentHistory->fetchAll();
                                                    ?>
                                                    
                                                    <?php if(count($assignments) > 0): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Employee</th>
                                                                        <th>Department</th>
                                                                        <th>Assigned Date</th>
                                                                        <th>Returned Date</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach($assignments as $assignment): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo htmlspecialchars($assignment['first_name'] . ' ' . $assignment['last_name']); ?>
                                                                                <?php if($assignment['email']): ?>
                                                                                    <br><small class="text-muted"><?php echo htmlspecialchars($assignment['email']); ?></small>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td><?php echo htmlspecialchars($assignment['departement'] ?? 'N/A'); ?></td>
                                                                            <td><?php echo date('M d, Y', strtotime($assignment['assigned_at'])); ?></td>
                                                                            <td>
                                                                                <?php if($assignment['returned_at']): ?>
                                                                                    <?php echo date('M d, Y', strtotime($assignment['returned_at'])); ?>
                                                                                <?php else: ?>
                                                                                    <span class="badge bg-success">Currently Assigned</span>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php if($assignment['returned_at']): ?>
                                                                                    <span class="badge bg-secondary">Returned</span>
                                                                                <?php else: ?>
                                                                                    <span class="badge bg-primary">Active</span>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-info">
                                                            <i class="bi bi-info-circle"></i> No assignment history found for this device.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="edit_hardware.php?id=<?php echo $hardware['id']; ?>" 
                                                   class="btn btn-modal">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Modal -->
                                <div class="modal fade" id="assignModal<?php echo $hardware['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-<?php echo $isAssigned ? 'person-dash' : 'person-plus'; ?>"></i>
                                                    <?php echo $isAssigned ? 'Unassign Device' : 'Assign Device'; ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="assign_hardware.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="hardware_id" value="<?php echo $hardware['id']; ?>">
                                                    
                                                    <?php if($isAssigned): ?>
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                            <strong>Currently assigned to:</strong><br>
                                                            <?php echo htmlspecialchars($hardware['first_name'] . ' ' . $hardware['last_name']); ?>
                                                            <?php if($hardware['departement']): ?>
                                                                <br>Department: <?php echo htmlspecialchars($hardware['departement']); ?>
                                                            <?php endif; ?>
                                                            <?php if($hardware['assigned_at']): ?>
                                                                <br>Since: <?php echo date('M d, Y', strtotime($hardware['assigned_at'])); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="return_notes" class="form-label">Return Notes (Optional)</label>
                                                            <textarea class="form-control" id="return_notes" name="return_notes" rows="2" placeholder="Reason for return..."></textarea>
                                                        </div>
                                                        <input type="hidden" name="action" value="unassign">
                                                    <?php else: ?>
                                                        <div class="mb-3">
                                                            <label for="employee_id" class="form-label">Assign to Employee</label>
                                                            <select class="form-select" id="employee_id" name="employee_id" required>
                                                                <option value="">Select an employee...</option>
                                                                <?php
                                                                $employees = $pdo->query("
                                                                    SELECT e.*, ec.name as category_name 
                                                                    FROM employees e 
                                                                    LEFT JOIN employee_categories ec ON e.category_id = ec.id
                                                                    ORDER BY e.first_name, e.last_name
                                                                ")->fetchAll();
                                                                foreach($employees as $employee):
                                                                ?>
                                                                    <option value="<?php echo $employee['id']; ?>">
                                                                        <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
                                                                        <?php if($employee['departement']): ?>
                                                                            - <?php echo htmlspecialchars($employee['departement']); ?>
                                                                        <?php endif; ?>
                                                                        <?php if($employee['category_name']): ?>
                                                                            (<?php echo htmlspecialchars($employee['category_name']); ?>)
                                                                        <?php endif; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="assign_notes" class="form-label">Assignment Notes (Optional)</label>
                                                            <textarea class="form-control" id="assign_notes" name="assign_notes" rows="2" placeholder="Purpose of assignment..."></textarea>
                                                        </div>
                                                        <input type="hidden" name="action" value="assign">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-modal">
                                                        <?php echo $isAssigned ? 'Unassign Device' : 'Assign Device'; ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inboxes"></i>
                    <h4>No hardware devices found</h4>
                    <p>
                        <?php if(!empty($search)): ?>
                            No devices match your search criteria.
                        <?php else: ?>
                            Get started by adding your first hardware device.
                        <?php endif; ?>
                    </p>
                    <a href="add_hardware.php" class="btn-add">
                        <i class="bi bi-plus-circle"></i> Add New Device
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#hardwareTable').DataTable({
                "pageLength": 25,
                "order": [[0, 'desc']],
                "language": {
                    "search": "Filter:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
                "initComplete": function() {
                    // Add custom styling to DataTables elements
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    
                    // Add export button
                    addExportButton();
                }
            });
        });

        // Confirm delete function
        function confirmDelete(hardwareId) {
            if (confirm('Are you sure you want to delete this hardware device? This action cannot be undone.')) {
                window.location.href = 'delete_hardware.php?id=' + hardwareId;
            }
        }

        // Quick search functionality (Ctrl+K)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
            }
        });

        // Auto-refresh search results
        let searchInput = document.querySelector('input[name="search"]');
        let timeout = null;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    if (searchInput.value.length >= 3 || searchInput.value.length === 0) {
                        searchInput.form.submit();
                    }
                }, 500);
            });
        }

        // Show success/error messages from session
        <?php if(isset($_SESSION['success'])): ?>
            showNotification('<?php echo $_SESSION['success']; ?>', 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            showNotification('<?php echo $_SESSION['error']; ?>', 'error');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        function showNotification(message, type) {
            // Remove any existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} notification alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="bi ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Export functionality
        function exportToCSV() {
            const table = document.getElementById('hardwareTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('th, td');
                cells.forEach(cell => {
                    // Remove action buttons from export
                    if (!cell.querySelector('.action-buttons')) {
                        let text = cell.textContent.trim();
                        // Remove extra whitespace and newlines
                        text = text.replace(/\s+/g, ' ').replace(/,/g, '');
                        rowData.push(`"${text}"`);
                    }
                });
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'hardware_inventory_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Add export button
        function addExportButton() {
            const tableWrapper = document.querySelector('.dataTables_wrapper');
            if (tableWrapper && !document.getElementById('exportBtn')) {
                const exportBtn = document.createElement('button');
                exportBtn.id = 'exportBtn';
                exportBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
                exportBtn.innerHTML = '<i class="bi bi-download me-1"></i> Export CSV';
                exportBtn.onclick = exportToCSV;
                
                const lengthDiv = tableWrapper.querySelector('.dataTables_length');
                if (lengthDiv) {
                    lengthDiv.appendChild(exportBtn);
                }
            }
        }

        // Calculate and display row totals
        function calculateRowTotals() {
            const table = document.getElementById('hardwareTable');
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            let totalPrice = 0;
            let availablePrice = 0;
            let inUsePrice = 0;
            let maintenancePrice = 0;
            
            rows.forEach(row => {
                const priceCell = row.querySelector('td:nth-child(7)');
                const statusCell = row.querySelector('td:nth-child(6)');
                
                if (priceCell && statusCell) {
                    const priceText = priceCell.textContent.trim();
                    const statusText = statusCell.textContent.trim().toLowerCase();
                    
                    // Extract price value
                    const priceMatch = priceText.match(/\$([\d,]+\.?\d*)/);
                    if (priceMatch) {
                        const price = parseFloat(priceMatch[1].replace(/,/g, ''));
                        if (!isNaN(price)) {
                            totalPrice += price;
                            
                            if (statusText.includes('available')) {
                                availablePrice += price;
                            } else if (statusText.includes('in use')) {
                                inUsePrice += price;
                            } else if (statusText.includes('maintenance')) {
                                maintenancePrice += price;
                            }
                        }
                    }
                }
            });
            
            // Update the price summary if needed
            updatePriceSummary(totalPrice, availablePrice, inUsePrice, maintenancePrice);
        }

        function updatePriceSummary(total, available, inUse, maintenance) {
            // This function can be used to dynamically update price summaries
            // if you want to update them based on filtered data
            console.log('Total Price:', total);
            console.log('Available Price:', available);
            console.log('In Use Price:', inUse);
            console.log('Maintenance Price:', maintenance);
        }

        // Initialize calculations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            calculateRowTotals();
            
            // Add animation to stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
            
            // Add animation to price summary
            const priceSummary = document.querySelector('.price-summary');
            if (priceSummary) {
                priceSummary.classList.add('animate__animated', 'animate__fadeIn');
            }
        });

        // Handle DataTable draw event to recalculate totals
        $('#hardwareTable').on('draw.dt', function() {
            calculateRowTotals();
        });

        // Print functionality
        function printTable() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Hardware Inventory Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #8b4513; text-align: center; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th { background-color: #333; color: white; padding: 10px; text-align: left; }
                        td { padding: 8px; border-bottom: 1px solid #ddd; }
                        .total-row { font-weight: bold; background-color: #f8f5f0; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Hardware Inventory Report</h1>
                    <p>Generated on: ${new Date().toLocaleDateString()}</p>
                    ${document.getElementById('hardwareTable').outerHTML}
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() {
                                window.close();
                            };
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Add print button
        function addPrintButton() {
            const tableWrapper = document.querySelector('.dataTables_wrapper');
            if (tableWrapper && !document.getElementById('printBtn')) {
                const printBtn = document.createElement('button');
                printBtn.id = 'printBtn';
                printBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
                printBtn.innerHTML = '<i class="bi bi-printer me-1"></i> Print';
                printBtn.onclick = printTable;
                
                const lengthDiv = tableWrapper.querySelector('.dataTables_length');
                if (lengthDiv) {
                    lengthDiv.appendChild(printBtn);
                }
            }
        }

        // Initialize DataTable with print button
        $(document).ready(function() {
            $('#hardwareTable').DataTable({
                "pageLength": 25,
                "order": [[0, 'desc']],
                "language": {
                    "search": "Filter:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
                "initComplete": function() {
                    // Add custom styling to DataTables elements
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    
                    // Add export and print buttons
                    addExportButton();
                    addPrintButton();
                }
            });
        });
    </script>
</body>
</html>