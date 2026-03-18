<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
// Your original backend logic - completely untouched
$actionSuccess = false;

// Pre-filter categories
$validCategories = array_filter($categories, function($cat) {
    return strtolower($cat['name'] ?? '') !== "uncategorized devices";
});



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($currentCategoryName) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="http://localhost/hardwareTracker/css/hardware/hardware_category.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        body {
            background: #f8f5f0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        nav {
            background: #333 !important;
            border-bottom: 2px solid #ffd700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        nav a {
            transition: color 0.3s ease;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
        }

        nav a:hover {
            color: #ffd700 !important;
            background: rgba(255,215,0,0.1);
        }

        .page-header {
            text-align: center;
            margin: 2rem 0 3rem;
        }

        .page-title {
            color: #333;
            font-size: 2.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .page-title i {
            color: #8b4513;
            font-size: 1.8rem;
        }

        .hardware-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .hardware-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            border-left-color: #8b4513;
        }

        .hardware-name {
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .hardware-name .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3em 0.7em;
            margin-left: 0.5rem;
        }

        .hardware-name i {
            color: #a0522d;
        }
        
        .action-buttons {
            display: flex;
            align-items: stretch;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .action-buttons form {
            margin: 0;
            display: flex;
        }

        .action-buttons form button,
        .action-buttons > button {
            flex-grow: 1;
            height: 100%;
        }
        
        /* ✨ NEW: Style for the Details button */
        .btn-details {
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-details:hover {
            background-color: #5a6268;
            transform: scale(1.03);
        }

        .btn-edit { background: #a0522d; color: white; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .btn-edit:hover { background: #8b4513; transform: scale(1.03); }
        .btn-assign { background: #8b4513; color: white; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .btn-assign:hover { background: #654321; transform: scale(1.03); }
        .btn-return { background: #d2691e; color: white; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .btn-return:hover { background: #b8860b; transform: scale(1.03); }
        .btn-uncategorize { background: #e76f51; color: white; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .btn-uncategorize:hover { background: #d45d40; transform: scale(1.03); }
        
        .fixed-add-buttons { position: fixed; bottom: 2rem; right: 2rem; z-index: 1000; display: flex; flex-direction: column; gap: 1rem; }
        .btn-fixed-add { background: #ffd700; color: #333; border: none; border-radius: 10px; padding: 1rem 1.5rem; font-size: 1rem; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s ease; display: flex; align-items: center; gap: 0.7rem; }
        .btn-fixed-add:hover { background: #ffed4e; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
        
        .empty-state { text-align: center; padding: 3rem 2rem; background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin: 2rem 0; }
        .empty-state i { color: #8b4513; font-size: 4rem; margin-bottom: 1.5rem; }
        .empty-state h4 { color: #333; font-size: 1.6rem; margin-bottom: 0.8rem; }
        .empty-state p { color: #555; font-size: 1rem; }

        @media (max-width: 768px) {
            .btn-fixed-add { padding: 0.8rem 1.2rem; font-size: 0.9rem; }
            .fixed-add-buttons { bottom: 1rem; right: 1rem; }
        }

        .assign-popup, #noticeEmployees, #noticeCategories, .details-modal { /* ✨ Added .details-modal */
            display: none;
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            padding: 1.5rem;
            width: 90%;
            max-width: 450px;
        }

        .assign-popup h3, .details-modal h3 { color: #333; font-weight: 600; margin-bottom: 1rem; }
        .assign-popup select { width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #e0d8cc; margin-bottom: 1rem; font-size: 0.9rem; }
        
        /* ✨ NEW: Styles for the details modal content */
        #modalBody .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0ebe5;
            font-size: 0.95rem;
        }
        #modalBody .detail-item:last-child {
            border-bottom: none;
        }
        #modalBody .detail-key {
            font-weight: 500;
            color: #555;
            margin-right: 1rem;
        }
        #modalBody .detail-value {
            color: #333;
            text-align: right;
        }

        .btn-secondary { background-color: #e0d8cc; color: #333; border: none; border-radius: 8px; }
        .btn-secondary:hover { background-color: #d0c4b0; color: #333; }
        #noticeEmployees, #noticeCategories { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        #noticeEmployees a, #noticeCategories a { color: #8b4513; font-weight: 600; text-decoration: none; }
        #noticeEmployees a:hover, #noticeCategories a:hover { text-decoration: underline; }
        
        .success-toast { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 2000; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 12px; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 20px rgba(0,0,0,0.15); animation: toastIn 0.4s ease forwards; max-width: 420px; min-width: 300px; }
        .success-toast .toast-icon { background: #28a745; color: white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .success-toast .toast-body { flex: 1; font-weight: 500; font-size: 0.95rem; }
        .success-toast .toast-close { background: none; border: none; color: #155724; font-size: 1.2rem; cursor: pointer; opacity: 0.6; transition: opacity 0.2s; padding: 0; line-height: 1; }
        .success-toast .toast-close:hover { opacity: 1; }
        .success-toast.toast-out { animation: toastOut 0.3s ease forwards; }
        @keyframes toastIn { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100px); } }
        @media (max-width: 768px) { .success-toast { top: 1rem; right: 1rem; left: 1rem; max-width: none; min-width: auto; } }
        .backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: none; }
    </style>
</head>
<body>
    <main class="container-lg">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-laptop"></i>
                <?= htmlspecialchars($currentCategoryName) ?>
            </h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <?php if (!empty($hardwares)): ?>

                    <?php foreach ($hardwares as $hardware): ?>
                    
                        <?php $hardwareName = $hardware['name'];
                       
                         ?>
                        <?php $hardwareId = $hardware['id']; ?>
                        <?php $status = $hardware['status']; ?>

                        <div class="hardware-card">
                            <div class="hardware-name">
                                <i class="bi bi-cpu"></i>
                                <span><?= htmlspecialchars($hardwareName) ?></span>
                                <?php
                                    
                                    $badgeClass = 'bg-secondary';
                                    if ($status == 'available') $badgeClass = 'bg-success';
                                    if ($status == 'assigned') $badgeClass = 'bg-primary';
                                ?>
                                <span class="badge rounded-pill status-badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars(ucfirst($status)) ?>
                                </span>
                            </div>

                            <div class="action-buttons">
                                <!-- ✨ NEW: Details Button -->
                                <form action="/hardwareTracker/hardwareCategory" method="post" data-action="edit" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                    <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                    <input type="hidden" name="hardware_status" value="<?= $hardware['status'] ?>">
                                    <input type="hidden" name="serial_number" value="<?= $hardware['serial_number'] ?>">
                                    <input type="hidden" name="hardware_name" value="<?= $hardware['name'] ?>">
                                    <input type="hidden" name="purchase_date" value="<?= $hardware['purchase_date'] ?>">
                                    <input type="hidden" name="received_date" value="<?= $hardware['received_date'] ?>">
                                    <input type="hidden" name="price" value="<?= $hardware['price'] ?>">
                                    <button type="submit" name="action" value="ShowHardwareDetails" class="btn-edit">
                                        <i class="bi bi-pencil"></i> Details
                                    </button>
                                </form>
                                
                                <!-- Edit Button -->
                                <form action="/hardwareTracker/hardwareCategory" method="post" data-action="edit" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                    <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                    <input type="hidden" name="hardware_status" value="<?= $hardware['status'] ?>">
                                    <input type="hidden" name="serial_number" value="<?= $hardware['serial_number'] ?>">
                                    <input type="hidden" name="hardware_name" value="<?= $hardware['name'] ?>">
                                    <input type="hidden" name="purchase_date" value="<?= $hardware['purchase_date'] ?>">
                                    <input type="hidden" name="received_date" value="<?= $hardware['received_date'] ?>">
                                    <input type="hidden" name="price" value="<?= $hardware['price'] ?>">
                                    <button type="submit" name="action" value="editHardware" class="btn-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>
                            
                                <!-- Assign/Return Button -->
                                <?php if ($status == "available") { ?>
                                    <button onclick="display(<?= $hardwareId ?>, <?=(!empty($employees) ? 1 : 0)?>)" class="btn-assign">
                                        <i class="bi bi-person-plus"></i> Assign to Employee
                                    </button>
                                <?php } elseif ($status == "assigned") { ?>
                                    <form action="/hardwareTracker/hardwareCategory" method="post" data-action="return" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                        <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                        <button type="submit" name="operation" value="returnHardware" class="btn-return">
                                            <i class="bi bi-box-arrow-in-left"></i> Return to Inventory
                                        </button>
                                    </form>
                                <?php } ?>
                                
                                <!-- Add to Category / Uncategorize Button -->
                                <?php if ($isUncategorized): ?>
                                    <button onclick="displayAddCat(<?= $hardwareId ?>, <?= count($validCategories)?>) " class="btn-assign">
                                        <i class="bi bi-folder-plus"></i> Add to Category
                                    </button>
                                <?php else: ?>

                                <?php endif; ?>
                            </div>
                        </div>
                
                        <!-- Assign Popup -->
                        <div id="assign-<?= $hardware['id'] ?>" class="assign-popup">
                            <h3>Assign to Employee</h3>
                            <form action="/hardwareTracker/hardwareCategory" method="post" data-action="assign" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                <label for="employee">Select Employee:</label>
                                <select name="employee_id" required>
                                    <option value="" disabled selected>Choose an employee</option>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?= $employee['id'] ?>">
                                            <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                <input type="hidden" name="operation" value="assignHardwareToEmployee">
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn-assign"><i class="bi bi-check"></i> Assign</button>
                                    <button type="button" onclick="closePopup(<?= $hardware['id'] ?>)" class="btn-secondary"><i class="bi bi-x"></i> Cancel</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Add to Category Popup -->
                        <?php if ($isUncategorized): ?>
                            <div id="addcat-<?= $hardware['id'] ?>" class="assign-popup">
                                <h3>Add to Category</h3>
                                <form action="/hardwareTracker/hardwareCategory" method="post" data-action="addCategory" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                    <label for="category">Select Category:</label>
                                    <select name="category_id" required>
                                        <option value="" disabled selected>Choose a category</option>
                                        <?php foreach ($validCategories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                    <input type="hidden" name="operation" value="addHardwareToCategory">
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn-assign"><i class="bi bi-check"></i> Add</button>
                                        <button type="button" onclick="closeAddCat(<?= $hardware['id'] ?>)" class="btn-secondary"><i class="bi bi-x"></i> Cancel</button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-folder-open"></i>
                        <h4>No Devices Yet</h4>
                        <p>Start by adding your first device using the button below!</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Fixed Add Buttons -->
    <div class="fixed-add-buttons">
        <form action="/hardwareTracker/hardwareCategory" method="post">
            <input type="hidden" name="action" value="addHardware">
            <input type="hidden" name="isuncategorized" value="<?= $isUncategorized ?>">
            <input type="hidden" name="category_id" value="<?= $currentCategoryId ?>">
            <button type="submit" class="btn-fixed-add"><i class="bi bi-plus-circle"></i> Add A Device</button>
        </form>
    </div>

    <!-- ✨ NEW: Single, Reusable Details Modal -->
    <div id="detailsModal" class="details-modal">
        <h3 id="modalTitle">Device Details</h3>
        <div id="modalBody">
            <!-- Content will be injected by JavaScript -->
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="button" onclick="closeDetails()" class="btn-secondary"><i class="bi bi-x-lg"></i> Close</button>
        </div>
    </div>

    <!-- Notices -->
    <div id="noticeEmployees">
        <p>Notice: No employee available to assign to, please add one in <a href="/hardwareTracker/employees">Employees page</a></p>
        <button onclick="closePopup(0)" class="btn-secondary">Close</button>
    </div>
    <div id="noticeCategories">
        <p>Notice: No categories available to add to, please add one in <a href="/hardwareTracker/categories">categories page</a></p>
        <button onclick="closePopup(0)" class="btn-secondary">Close</button>
    </div>

    <!-- Backdrop -->
    <div class="backdrop" id="backdrop"></div>
    <?php if ($actionSuccess): ?>
            showToast('Device uncategorized successfully!', 'folder-minus');
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="http://localhost/hardwareTracker/js/hardware/hardware_category.js"></script>
    <script>
                // ── Original Popup Logic (unchanged) ──────────────────
        function display(hardware_id, hasEmployees) {
            if (hasEmployees == 0) {
                document.getElementById("noticeEmployees").style.display = "block";
            } else {
                document.getElementById("assign-" + hardware_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }

        function closePopup(hardware_id) {
            if (hardware_id == 0) {
                document.getElementById("noticeEmployees").style.display = "none";
                document.getElementById("noticeCategories").style.display = "none";
            } else {
                document.getElementById("assign-" + hardware_id).style.display = "none";
            }
            document.getElementById("backdrop").style.display = "none";
        }

        function displayAddCat(hardware_id, categoryCount) {
            if (categoryCount == 0) {
                document.getElementById("noticeCategories").style.display = "block";
            } else {
                document.getElementById("addcat-" + hardware_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }

        function closeAddCat(hardware_id) {
            document.getElementById("addcat-" + hardware_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }
        
        // ✨ NEW: Details Modal Functions
        function showDetails(hardware) {
            const modal = document.getElementById('detailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');

            // Set the title
            modalTitle.textContent = hardware.hardware_name + ' Details';
            
            // Clear previous details
            modalBody.innerHTML = '';
            
            // Define which details to show and their labels
            const detailsToShow = {
                'hardware_name': 'Name',
                'serial_number': 'Serial Number',
                'hardware_status': 'Status',
                'purchase_date': 'Purchase Date',
                'received_date': 'Received Date',
                'price': 'Price'
            };

            // Function to format the date nicely
            function formatDate(dateString) {
                if (!dateString || dateString === '0000-00-00') return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            }

            for (const key in detailsToShow) {
                if (hardware.hasOwnProperty(key)) {
                    let value = hardware[key];
                    
                    // Format dates
                    if (key === 'purchase_date' || key === 'received_date') {
                        value = formatDate(value);
                    }
                    
                    // Capitalize status
                    if (key === 'hardware_status') {
                        value = value.charAt(0).toUpperCase() + value.slice(1);
                    }

                    const item = document.createElement('div');
                    item.className = 'detail-item';
                    item.innerHTML = `<span class="detail-key">${detailsToShow[key]}</span> <span class="detail-value">${value || 'N/A'}</span>`;
                    modalBody.appendChild(item);
                }
            }

            modal.style.display = 'block';
            document.getElementById('backdrop').style.display = 'block';
        }

        function closeDetails() {
            document.getElementById('detailsModal').style.display = 'none';
            document.getElementById('backdrop').style.display = 'none';
        }

        // ✨ UPDATED: Generic close handlers for backdrop and Escape key
        document.getElementById("backdrop").addEventListener("click", function() {
            document.querySelectorAll('.assign-popup, #noticeEmployees, #noticeCategories, .details-modal').forEach(function(el) {
                el.style.display = "none";
            });
            this.style.display = "none";
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.assign-popup, #noticeEmployees, #noticeCategories, .details-modal').forEach(function(el) {
                    el.style.display = "none";
                });
                document.getElementById("backdrop").style.display = "none";
            }
        });

        // ── Success Toast System (unchanged) ──────────────────────────────
        var successMessages = {
            'edit':         function(name) { return '<strong>' + name + '</strong> updated successfully!'; },
            'assign':       function(name) { return '<strong>' + name + '</strong> assigned successfully!'; },
            'return':       function(name) { return '<strong>' + name + '</strong> returned successfully!'; },
            'uncategorize': function(name) { return '<strong>' + name + '</strong> uncategorized successfully!'; },
            'addCategory':  function(name) { return '<strong>' + name + '</strong> added to category successfully!'; }
        };

        var actionIcons = {
            'edit':         'pencil',
            'assign':       'person-check',
            'return':       'box-arrow-in-left',
            'uncategorize': 'folder-minus',
            'addCategory':  'folder-plus'
        };

        function showToast(message, icon) {
            document.querySelectorAll('.success-toast').forEach(function(t) {
                t.classList.add('toast-out');
                setTimeout(function() { t.remove(); }, 300);
            });

            var toast = document.createElement('div');
            toast.className = 'success-toast';
            toast.innerHTML =
                '<span class="toast-icon"><i class="bi bi-' + (icon || 'check-lg') + '"></i></span>' +
                '<span class="toast-body">' + message + '</span>' +
                '<button class="toast-close" onclick="dismissToast(this.parentElement)" aria-label="Close">&times;</button>';
            document.body.appendChild(toast);

            setTimeout(function() { dismissToast(toast); }, 4000);
        }

        function dismissToast(el) {
            if (!el || !el.parentElement) return;
            el.classList.add('toast-out');
            setTimeout(function() { el.remove(); }, 300);
        }

        // ── Store action info before form submits (unchanged) ─────────────
        document.querySelectorAll('form[data-action]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var action = this.getAttribute('data-action');
                var device = this.getAttribute('data-device');
                if (action && successMessages[action]) {
                    sessionStorage.setItem('toast_action', action);
                    sessionStorage.setItem('toast_device', device);
                }
            });
        });

        // ── Check for pending toast on page load (unchanged) ──────────────
        (function checkPendingToast() {
            var action = sessionStorage.getItem('toast_action');
            var device = sessionStorage.getItem('toast_device');
            if (action && successMessages[action]) {
                var message = successMessages[action](device || 'Device');
                var icon = actionIcons[action] || 'check-lg';
                showToast(message, icon);
            }
            sessionStorage.removeItem('toast_action');
            sessionStorage.removeItem('toast_device');
        })();

        // ── PHP-side uncategorize success (unchanged) ─────────────────────

        // ── Prevent form resubmission on refresh (unchanged) ──────────────
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>