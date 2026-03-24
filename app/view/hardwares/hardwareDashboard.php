



<div class="container-fluid">
<div class="row">
    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Devices -->
        <div class="col-md-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">All Devices</h5>
                </div>
                
                <?php if(count($hardwares) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Device Logs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($hardwares as $device): 
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
                                            <form action="deviceLogs" method="post" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $hardware['id']?>">
                                                <button class="btn btn-outline-warning rounded-pill" type="submit">View Logs</button>
                                            </form>
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
                                                            case 'assigned': echo '#17a2b8'; break;
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
                                    <span>In Use Devices (Assigned):</span>
                                    <strong>$<?php echo number_format($inUsePrice, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>In Maintenance:</span>
                                    <strong>$<?php echo number_format($maintenance_price, 2); ?></strong>
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