<?php
$actionSuccess = false;

// Pre-filter categories
$validCategories = array_filter($categories, function($cat) {
    return strtolower($cat['name'] ?? '') !== "uncategorized devices";
});
$activeEmployee = array_filter($employees, function($employee) {
    return $employee['status']  == 'active';
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
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/hardware/hardwareCategory.css">
</head>
<body>
    <main class="container-lg">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-laptop">
                    <?= htmlspecialchars($currentCategoryName) ?>
                </i>       
            </h1>
            <h4>
                <i class="bi bi-folder-open">
                    <?= htmlspecialchars($currentCategoryDescription) ?>   
                </i>  
            </h4>  
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <?php if (!empty($hardwares)): ?>

                    <?php foreach ($hardwares as $hardware):
                        $hardwareName = $hardware['name'];
                        $hardwareId = $hardware['id'];
                        $status = $hardware['status']; 
                    ?>

                        <div class="hardware-card">
                            <div class="hardware-name">
                                <i class="bi bi-cpu">
                                    <span><?= htmlspecialchars($hardwareName) ?></span>
                                </i>  
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
                                <!-- Details Button -->
                                <button class="btn-details" onclick='showDetails(<?= htmlspecialchars(json_encode($hardware), ENT_QUOTES, "UTF-8") ?>)'>
                                    <i class="bi bi-info-circle">  Details </i>
                                </button>

                                <!-- Edit Button -->
                                <form action="/hardwareTracker/hardwareCategory" method="post" data-action="edit" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                    <input type="hidden" name="turn_off_search" value="true">
                                    <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                    <input type="hidden" name="hardware_status" value="<?= $hardware['status'] ?>">
                                    <input type="hidden" name="serial_number" value="<?= $hardware['serial_number'] ?>">
                                    <input type="hidden" name="hardware_name" value="<?= $hardware['name'] ?>">
                                    <input type="hidden" name="purchase_date" value="<?= $hardware['purchase_date'] ?>">
                                    <input type="hidden" name="received_date" value="<?= $hardware['received_date'] ?>">
                                    <input type="hidden" name="price" value="<?= $hardware['price'] ?>">
                                    <button type="submit" name="action" value="editHardware" class="btn-edit">
                                        <i class="bi bi-pencil"> Edit </i> 
                                    </button>
                                </form>
                            
                                <!-- Assign/Return Button -->
                                <?php if ($status == "available") { ?>
                                    <button onclick="display(<?= $hardwareId ?>, <?=(!empty($activeEmployee) ? 1 : 0)?>)" class="btn-assign">
                                        <i class="bi bi-person-plus"> Assign to Employee </i> 
                                    </button>
                                <?php } elseif ($status == "assigned") { ?>
                                    <form action="/hardwareTracker/hardwareCategory" method="post" data-action="return" data-device="<?= htmlspecialchars($hardwareName) ?>">
                                        <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                        <button type="submit" name="operation" value="returnHardware" class="btn-return">
                                            <i class="bi bi-box-arrow-in-left"> Return to Inventory </i> 
                                        </button>
                                    </form>
                                <?php } ?>
                                
                                <!-- Add to Category / Uncategorize Button -->
                                <?php if ($isUncategorized): ?>
                                    <button onclick="displayAddCat(<?= $hardwareId ?>, <?= count($validCategories)?>) " class="btn-assign">
                                        <i class="bi bi-folder-plus"> Add to Category </i> 
                                    </button>
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
                                        <?php if($employee['status'] == 'active'): ?>
                                        <option value="<?= $employee['id'] ?>">
                                            <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                                <input type="hidden" name="operation" value="assignHardwareToEmployee">
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn-assign"><i class="bi bi-check">  Assign </i></button>
                                    <button type="button" onclick="closePopup(<?= $hardware['id'] ?>)" class="btn-secondary"><i class="bi bi-x"> Cancel </i> </button>
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
                                        <button type="submit" class="btn-assign"><i class="bi bi-check"> Add </i> </button>
                                        <button type="button" onclick="closeAddCat(<?= $hardware['id'] ?>)" class="btn-secondary"><i class="bi bi-x"> Cancel </i> </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if(!$inSreach): ?>
                    <div class="empty-state">
                        <i class="bi bi-folder-open">
                            <h4>No Devices Yet</h4>
                            <p>Start by adding your first device using the button below!</p>
                        </i>     
                    </div>
                    <?php endif;?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Fixed Add Buttons -->
    <div class="fixed-add-buttons">
        <form action="/hardwareTracker/hardwareCategory" method="post">
            <input type="hidden" name="action" value="addHardware">
            <input type="hidden" name="turn_off_search" value="true">       
            <input type="hidden" name="isuncategorized" value="<?= $isUncategorized ?>">
            <input type="hidden" name="category_id" value="<?= $currentCategoryId ?>">
            <button type="submit" class="btn-fixed-add"><i class="bi bi-plus-circle"> Add A Device </i></button>
        </form>
    </div>

    <!-- ✨ NEW: Single, Reusable Details Modal -->
    <div id="detailsModal" class="details-modal">
        <h3 id="modalTitle">Device Details</h3>
        <div id="modalBody">
           <!-- Content will be injected by JavaScript -->
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="button" onclick="closeDetails()" class="btn-secondary"><i class="bi bi-x-lg"> Close </i></button>
        </div>
    </div>

    <!-- Notices -->
    <div id="noticeEmployees">
        <p>Notice: No employee available or active to assign to, please add or active one in <a href="/hardwareTracker/employees">Employees page</a></p>
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
            modalTitle.textContent = hardware.name + ' Details';
            console.log(modalTitle);
            console.log(hardware);
            // Clear previous details
            modalBody.innerHTML = '';
            
            // Define which details to show and their labels
            const detailsToShow = {
                'name': 'Name',
                'serial_number': 'Serial Number',
                'status': 'Status',
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