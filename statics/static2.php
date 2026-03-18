<?php
// hardwares.php
session_start();
require_once 'db.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get statistics
$pdo = $db;
$totalHardware = $pdo->query("SELECT COUNT(*) FROM hardwares")->fetchColumn();
$assignedHardware = $pdo->query("SELECT COUNT(DISTINCT hardware_id) FROM assignments WHERE returned_at IS NULL")->fetchColumn();
$unassignedHardware = $totalHardware - $assignedHardware;

// Calculate total prices
$priceStats = $pdo->query("SELECT 
    SUM(price) as total_price,
    SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as available_price,
    SUM(CASE WHEN status = 'in_use' THEN price ELSE 0 END) as in_use_price
    FROM hardwares")->fetch(PDO::FETCH_ASSOC);

$totalPrice = $priceStats['total_price'] ?? 0;
$availablePrice = $priceStats['available_price'] ?? 0;
$inUsePrice = $priceStats['in_use_price'] ?? 0;

// Get recent hardware
$query = "SELECT h.*, c.name as category_name, e.first_name, e.last_name
          FROM hardwares h
          LEFT JOIN categories c ON h.category_id = c.id
          LEFT JOIN assignments a ON h.id = a.hardware_id AND a.returned_at IS NULL
          LEFT JOIN employees e ON a.employee_id = e.id
          ORDER BY h.created_at DESC LIMIT 10";

$recentHardware = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Get hardware by status
$statusStats = $pdo->query("SELECT status, COUNT(*) as count FROM hardwares GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .sidebar {
            background: var(--primary);
            color: white;
            min-height: 100vh;
            padding: 0;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--secondary);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 1.5rem;
        }
        
        .dashboard-header {
            margin-bottom: 2rem;
        }
        
        .dashboard-header h1 {
            color: var(--primary);
            font-weight: 600;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--secondary);
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card h3 {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-card p {
            color: #666;
            margin-bottom: 0;
        }
        
        .stats-card i {
            font-size: 2rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }
        
        .card-total { border-left-color: var(--primary); }
        .card-total i { color: var(--primary); }
        
        .card-assigned { border-left-color: var(--success); }
        .card-assigned i { color: var(--success); }
        
        .card-unassigned { border-left-color: var(--warning); }
        .card-unassigned i { color: var(--warning); }
        
        .card-value { border-left-color: var(--danger); }
        .card-value i { color: var(--danger); }
        
        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .dashboard-card h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light);
        }
        
        .table th {
            background: var(--light);
            color: var(--primary);
            font-weight: 600;
            border: none;
        }
        
        .table td {
            vertical-align: middle;
            border-color: var(--light);
        }
        
        .badge-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .badge-available { background: #d4edda; color: #155724; }
        .badge-in_use { background: #d1ecf1; color: #0c5460; }
        .badge-maintenance { background: #fff3cd; color: #856404; }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            border: none;
            margin: 0 2px;
            font-size: 0.875rem;
        }
        
        .btn-view { background: #e3f2fd; color: #1976d2; }
        .btn-edit { background: #fff3cd; color: #856404; }
        .btn-assign { background: #d4edda; color: #155724; }
        
        .search-box {
            background: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            border: 1px solid var(--light);
        }
        
        .search-box input {
            border: none;
            outline: none;
            width: 100%;
        }
        
        .quick-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .btn-quick {
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-quick:hover {
            background: #2980b9;
            color: white;
            transform: translateY(-2px);
        }
        
        .price-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-brand">
                    <h4><i class="bi bi-pc-display"></i> Hardware</h4>
                </div>
                <div class="sidebar-nav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="hardwares.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="hardwares_list.php">
                                <i class="bi bi-list-ul"></i> All Devices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_hardware.php">
                                <i class="bi bi-plus-circle"></i> Add Device
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
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                <i class="bi bi-bar-chart"></i> Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 ms-sm-auto px-4">
                <!-- Dashboard Header -->
                <div class="dashboard-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1>Hardware Dashboard</h1>
                            <p class="text-muted">Overview of your hardware inventory</p>
                        </div>
                        <div class="search-box">
                            <form method="GET" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       placeholder="Search devices..."
                                       value="<?php echo htmlspecialchars($search); ?>"
                                       class="flex-grow-1">
                                <button type="submit" class="btn btn-link text-dark">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="add_hardware.php" class="btn-quick">
                            <i class="bi bi-plus-circle"></i> Add Device
                        </a>
                        <a href="hardwares_list.php" class="btn-quick" style="background: var(--success);">
                            <i class="bi bi-list-ul"></i> View All
                        </a>
                        <a href="reports.php" class="btn-quick" style="background: var(--warning);">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
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

                <!-- Main Content Row -->
                <div class="row">
                    <!-- Recent Devices -->
                    <div class="col-md-8">
                        <div class="dashboard-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Devices</h5>
                                <a href="hardwares_list.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            
                            <?php if(count($recentHardware) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recentHardware as $device): 
                                                $isAssigned = !empty($device['first_name']);
                                                $status = $device['status'] ?? 'available';
                                                $statusClass = 'badge-' . str_replace('_', '-', $status);
                                            ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($device['name']); ?></strong>
                                                        <?php if($device['serial_number']): ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($device['serial_number']); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($device['category_name']): ?>
                                                            <?php echo htmlspecialchars($device['category_name']); ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge-status <?php echo $statusClass; ?>">
                                                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if($isAssigned): ?>
                                                            <?php echo htmlspecialchars($device['first_name'] . ' ' . $device['last_name']); ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Unassigned</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn-action btn-view" 
                                                                onclick="window.location.href='view_hardware.php?id=<?php echo $device['id']; ?>'">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn-action btn-edit"
                                                                onclick="window.location.href='edit_hardware.php?id=<?php echo $device['id']; ?>'">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn-action btn-assign"
                                                                onclick="window.location.href='assign_hardware.php?id=<?php echo $device['id']; ?>'">
                                                            <i class="bi bi-person-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="bi bi-inboxes"></i>
                                    <p>No devices found</p>
                                    <a href="add_hardware.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Add First Device
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Side Stats -->
                    <div class="col-md-4">
                        <!-- Status Distribution -->
                        <div class="dashboard-card mb-4">
                            <h5>Status Distribution</h5>
                            <div class="list-group">
                                <?php foreach($statusStats as $stat): 
                                    $status = $stat['status'] ?? 'available';
                                    $count = $stat['count'];
                                    $percentage = $totalHardware > 0 ? round(($count / $totalHardware) * 100) : 0;
                                ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="badge-status badge-<?php echo str_replace('_', '-', $status); ?> me-2">
                                                <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <strong><?php echo $count; ?></strong>
                                            <small class="text-muted">(<?php echo $percentage; ?>%)</small>
                                        </div>
                                    </div>
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div class="progress-bar" 
                                             style="width: <?php echo $percentage; ?>%;
                                                    background: <?php 
                                                        switch($status) {
                                                            case 'available': echo '#28a745'; break;
                                                            case 'in_use': echo '#17a2b8'; break;
                                                            case 'maintenance': echo '#ffc107'; break;
                                                            default: echo '#6c757d';
                                                        }
                                                    ?>;">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="dashboard-card">
                            <h5>Price Summary</h5>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Value:</span>
                                    <strong>$<?php echo number_format($totalPrice, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Available Devices:</span>
                                    <strong>$<?php echo number_format($availablePrice, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>In Use Devices:</span>
                                    <strong>$<?php echo number_format($inUsePrice, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Average per Device:</span>
                                    <strong>$<?php echo $totalHardware > 0 ? number_format($totalPrice / $totalHardware, 2) : '0.00'; ?></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="dashboard-card mt-4">
                            <h5>Quick Links</h5>
                            <div class="list-group list-group-flush">
                                <a href="add_hardware.php" class="list-group-item list-group-item-action">
                                    <i class="bi bi-plus-circle me-2"></i> Add New Device
                                </a>
                                <a href="assign_bulk.php" class="list-group-item list-group-item-action">
                                    <i class="bi bi-people me-2"></i> Bulk Assign
                                </a>
                                <a href="reports.php" class="list-group-item list-group-item-action">
                                    <i class="bi bi-bar-chart me-2"></i> Generate Report
                                </a>
                                <a href="export.php" class="list-group-item list-group-item-action">
                                    <i class="bi bi-download me-2"></i> Export Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple notifications
        <?php if(isset($_SESSION['success'])): ?>
            showNotification('<?php echo $_SESSION['success']; ?>', 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            showNotification('<?php echo $_SESSION['error']; ?>', 'error');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        // Auto-refresh dashboard every 30 seconds
        setInterval(() => {
            // You can implement auto-refresh here if needed
            // window.location.reload();
        }, 30000);
    </script>
</body>
</html>