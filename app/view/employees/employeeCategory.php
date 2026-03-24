<?php
$validCategories = array_filter($categories, function($cat) {
    return (($GLOBALS['inactive'] ? true : ($cat['id'] !== 1)) && $cat['id'] !== 2);
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
    <link rel="stylesheet" href="app/view/css/employee/employeeCategory.css">
    
    <style>
       /* Notice Modal */
.noticeCategories { 
    display: none ;
    background-color: #fff3cd; 
    color: #856404; 
    border: 1px solid #ffeeba;
    border-radius: 12px;
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
.noticeCategories a { color: #8b4513; font-weight: 600; text-decoration: none; }
.noticeCategories a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main class="container-lg">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-people-fill"></i>
                <?= htmlspecialchars($currentCategoryName) ?>
            </h1>
            <h4>
                <i class="bi bi-folder-open"></i> <?= htmlspecialchars($currentCategoryDescription) ?>
            </h4>  
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $employee): ?>
                        <?php $employeeName = $employee['first_name'] . ' ' . $employee['last_name']; ?>

                        <div class="employee-card">
                            <div class="employee-name">
                                <i class="bi bi-person-fill"></i>
                                <?= htmlspecialchars($employeeName) ?>
                                <?php 
                                    $status = $employee['status'];
                                    if ($status == 'active') $badgeClass = 'bg-success';
                                    if ($status == 'inactive') $badgeClass = 'bg-secondary';
                                ?>
                                <span class="badge rounded-pill status-badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars(ucfirst($status)) ?>
                                </span>
                            </div>

                            <div class="action-buttons">
                                <button onclick="showDetails(<?= $employee['id'] ?>)" class="btn-details">
                                    <i class="bi bi-eye"></i> View Details
                                </button>

                                <form action="/hardwareTracker/employeeCategory" method="post" data-action="edit" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                    <input type="hidden" name="turn_off_search" value="true">
                                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                    <input type="hidden" name="firstName" value="<?= $employee['first_name'] ?>">
                                    <input type="hidden" name="lastName" value="<?= $employee['last_name'] ?>">
                                    <input type="hidden" name="email" value="<?= $employee['email'] ?>">
                                    <input type="hidden" name="departement" value="<?= $employee['departement'] ?>">
                                    <button type="submit" name="action" value="editEmployee" class="btn-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>

                                <?php if($employee['status'] == 'active'): ?>
                                <form action="/hardwareTracker/employeeCategory" method="post" data-action="edit" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                    <button type="submit" name="operation" value="<?=($employee['status'] == 'active') ? 'inactive' : 'active'?>" class="btn-edit">
                                        <i class="bi bi-pencil"></i> Inactive
                                    </button>
                                </form>
                                <?php endif;?>

                                <?php if ($isUncategorized || $inactive): ?>
                                    <button onclick="displayAddCat(<?= $employee['id'] ?>, <?= count($validCategories)?>)" class="btn-add-category">
                                        <i class="bi bi-folder-plus"></i> <?= $inactive ? 'Active' : 'Add to Category' ?>
                                    </button>
                                <?php else: ?>
                                    <?php if($employee['status'] == 'active'): ?>
                                    <form action="/hardwareTracker/employeeCategory" method="post" data-action="uncategorize" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                        <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                        <button type="submit" name="operation" value="uncatigorizeEmployee" class="btn-uncategorize">
                                            <i class="bi bi-trash"></i> Uncategorize
                                        </button>
                                    </form>
                                    <?php endif;?>
                                <?php endif; ?>
                            </div> </div> <div id="details-<?= $employee['id'] ?>" class="details-popup">
                            <h3><i class="bi bi-info-circle"></i> Employee Details</h3>
                            <div class="details-content">
                                <div class="detail-item">
                                    <div class="detail-label">Full Name</div>
                                    <div class="detail-value"><?= htmlspecialchars($employeeName) ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value"><?= htmlspecialchars($employee['email'] ?? 'N/A') ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Department</div>
                                    <div class="detail-value"><?= htmlspecialchars($employee['departement'] ?? 'N/A') ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Category</div>
                                    <div class="detail-value"><?= htmlspecialchars($currentCategoryName) ?></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" onclick="closeDetails(<?= $employee['id'] ?>)" class="btn-secondary">
                                    <i class="bi bi-x-lg"></i> Close
                                </button>
                            </div>
                        </div>

                        <?php if ($isUncategorized || $inactive): ?>
                        <div id="addcat-<?= $employee['id'] ?>" class="assign-popup">
                            <h3><i class="bi bi-folder-plus"></i> <?= $inactive ? 'Choose a category' : 'Add to Category' ?></h3>
                            <form action="/hardwareTracker/employeeCategory" method="post" data-action="addCategory" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                <label for="category">Select Category:</label>
                                <select name="category_id" required>
                                    <option value="" disabled selected>Choose a category</option>
                                    <?php foreach ($validCategories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                <input type="hidden" name="operation" value="<?= $inactive ? 'active':'addEmployeeToCategory'?>">
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn-add-category">
                                        <i class="bi bi-check"></i> Add
                                    </button>
                                    <button type="button" onclick="closeAddCat(<?= $employee['id'] ?>)" class="btn-secondary">
                                        <i class="bi bi-x"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h4>No Employees Yet</h4>
                        <p>Start by adding your first employee using the yellow button below!</p>
                    </div>
                <?php endif; ?>                
                <div id="noticeCategories" class="noticeCategories">
                    <p>Notice: No categories available to add to, please add one in the <a href="/hardwareTracker/employees">employees page</a>.</p>
                    <button onclick="closePopup()" class="btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </main>


    <div class="fixed-add-buttons">
        <form action="/hardwareTracker/employeeCategory" method="post">
            <input type="hidden" name="action" value="addEmployee">
            <input type="hidden" name="turn_off_search" value="true">
            <input type="hidden" name="category_id" value="<?= $currentCategoryId ?>">
            <input type="hidden" name="isuncategorized" value="false">
            <button type="submit" class="btn-fixed-add">
                <i class="bi bi-person-plus"></i> Add Employee
            </button>
        </form>
    </div>

    <div class="backdrop" id="backdrop"></div>

    <script>
        // Modal & Backdrop Displays
        function showDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "block";
            document.getElementById("backdrop").style.display = "block";
        }

        function closeDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        function displayAddCat(employee_id, categoryCount) {
            if (categoryCount == 0) {
                document.getElementById("noticeCategories").style.display = "block";
            } else {
                document.getElementById("addcat-" + employee_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }

        function closeAddCat(employee_id) {
            document.getElementById("addcat-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Newly added closePopup function to fix the bug
        function closePopup() {
            document.getElementById("noticeCategories").style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Backdrop click handler
        document.getElementById("backdrop").addEventListener("click", function() {
            document.querySelectorAll('.details-popup, .assign-popup, #noticeCategories').forEach(function(el) {
                el.style.display = "none";
            });
            this.style.display = "none";
        });

        // Escape key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.details-popup, .assign-popup, #noticeCategories').forEach(function(el) {
                    el.style.display = "none";
                });
                document.getElementById("backdrop").style.display = "none";
            }
        });

        // Toast System
        var successMessages = {
            'edit': function(name) { return '<strong>' + name + '</strong> updated successfully!'; },
            'uncategorize': function(name) { return '<strong>' + name + '</strong> uncategorized successfully!'; },
            'addCategory': function(name) { return '<strong>' + name + '</strong> added to category successfully!';},
            'desactivate': function(name) { return '<strong>' + name + '</strong> status updated successfully!'; } 
        };

        var actionIcons = {
            'edit': 'pencil',
            'uncategorize': 'folder-minus',
            'addCategory': 'folder-plus'
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

        // Store action info
        document.querySelectorAll('form[data-action]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var action = this.getAttribute('data-action');
                var employee = this.getAttribute('data-employee');
                if (action && successMessages[action]) {
                    sessionStorage.setItem('toast_action', action);
                    sessionStorage.setItem('toast_employee', employee);
                }
            });
        });

        // Check pending toast
        (function checkPendingToast() {
            var action = sessionStorage.getItem('toast_action');
            var employee = sessionStorage.getItem('toast_employee');
            if (action && successMessages[action]) {
                var message = successMessages[action](employee || 'Employee');
                var icon = actionIcons[action] || 'check-lg';
                showToast(message, icon);
            }
            sessionStorage.removeItem('toast_action');
            sessionStorage.removeItem('toast_employee');
        })();

        // Prevent resubmission on page reload
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
