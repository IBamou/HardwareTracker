<?php




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($currentCategoryName) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="http://localhost/hardwareTracker/css/employee/employee_categories.css">
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

        .employee-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .employee-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            border-left-color: #8b4513;
        }

        .employee-name {
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .employee-name i {
            color: #a0522d;
        }

        .action-buttons {
            display: flex;
            gap: 0.7rem;
            flex-wrap: nowrap;
            align-items: stretch;
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.25rem;
        }

        .action-buttons form {
            display: flex;
            height: 100%;
            margin: 0;
        }

        .btn-edit, .btn-uncategorize, .btn-details, .btn-add-category {
            background: #a0522d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: 100%;
            white-space: nowrap;
            min-width: fit-content;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-edit {
            background: #a0522d;
        }
        .btn-edit:hover {
            background: #8b4513;
            transform: translateY(-2px);
        }

        .btn-uncategorize {
            background: #e76f51;
        }
        .btn-uncategorize:hover {
            background: #d45d40;
            transform: translateY(-2px);
        }

        .btn-details {
            background: #4a6fa5;
        }
        .btn-details:hover {
            background: #365880;
            transform: translateY(-2px);
        }

        .btn-add-category {
            background: #8b4513;
        }
        .btn-add-category:hover {
            background: #654321;
            transform: translateY(-2px);
        }

        .fixed-add-buttons {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-fixed-add {
            background: #ffd700;
            color: #333;
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .btn-fixed-add:hover {
            background: #ffed4e;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin: 2rem 0;
        }

        .empty-state i {
            color: #8b4513;
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: #333;
            font-size: 1.6rem;
            margin-bottom: 0.8rem;
        }

        .empty-state p {
            color: #555;
            font-size: 1rem;
        }

        /* Popups */
        .details-popup, .assign-popup {
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
            max-width: 500px;
        }

        .details-popup h3, .assign-popup h3 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .assign-popup select {
            width: 100%;
            padding: 0.7rem;
            border-radius: 8px;
            border: 1px solid #e0d8cc;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .btn-secondary {
            background-color: #e0d8cc;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.2rem;
        }

        .btn-secondary:hover {
            background-color: #d0c4b0;
            color: #333;
        }

        /* Details Content */
        .details-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            background: #f8f5f0;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border-left: 3px solid #8b4513;
        }

        .detail-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #666;
            font-weight: 600;
            margin-bottom: 0.3rem;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
            word-break: break-word;
        }

        /* Toast */
        .success-toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 2000;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            animation: toastIn 0.4s ease forwards;
            max-width: 420px;
            min-width: 300px;
        }

        .success-toast .toast-icon {
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .success-toast .toast-body {
            flex: 1;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .success-toast .toast-close {
            background: none;
            border: none;
            color: #155724;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
            padding: 0;
            line-height: 1;
        }

        .success-toast .toast-close:hover {
            opacity: 1;
        }

        .success-toast.toast-out {
            animation: toastOut 0.3s ease forwards;
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }

        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            display: none;
        }

        @media (max-width: 768px) {
            .btn-fixed-add {
                padding: 0.8rem 1.2rem;
                font-size: 0.9rem;
            }
            .fixed-add-buttons {
                bottom: 1rem;
                right: 1rem;
            }
            .success-toast {
                top: 1rem;
                right: 1rem;
                left: 1rem;
                max-width: none;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <main class="container-lg">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-people-fill"></i>
                <?= htmlspecialchars($currentCategoryName) ?>
            </h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">

                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $employee): ?>
                        <?php 
                            $employeeName = $employee['first_name'] . ' ' . $employee['last_name'];
                        ?>

                        <div class="employee-card">
                            <div class="employee-name">
                                <i class="bi bi-person-fill"></i>
                                <?= htmlspecialchars($employeeName) ?>
                            </div>

                            <div class="action-buttons">
                                <!-- Details Button -->
                                <button onclick="showDetails(<?= $employee['id'] ?>)" class="btn-details">
                                    <i class="bi bi-eye"></i> View Details
                                </button>

                                <!-- Edit Button -->
                                <form action="/hardwareTracker/employeeCategory" method="post" data-action="edit" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                    <input type="hidden" name="firstName" value="<?= $employee['first_name'] ?>">
                                    <input type="hidden" name="lastName" value="<?= $employee['last_name'] ?>">
                                    <input type="hidden" name="email" value="<?= $employee['email'] ?>">
                                    <input type="hidden" name="departement" value="<?= $employee['departement'] ?>">
                                    <button type="submit" name="action" value="editEmployee" class="btn-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>

                                <!-- Add to Category / Uncategorize Button -->
                                <?php if ($isUncategorized): ?>
                                    <button onclick="showAddCategory(<?= $employee['id'] ?>)" class="btn-add-category">
                                        <i class="bi bi-folder-plus"></i> Add to Category
                                    </button>
                                <?php else: ?>
                                    <form action="/hardwareTracker/employeeCategory" method="post" data-action="uncategorize" data-device="<?= htmlspecialchars($employeeName) ?>">
                                        <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                        <button type="submit" name="operation" value="uncatigorizeEmployee" class="btn-uncategorize">
                                            <i class="bi bi-trash"></i> Uncategorize
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Details Popup -->
                        <div id="details-<?= $employee['id'] ?>" class="details-popup">
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

                        <!-- Add to Category Popup -->
                        <?php if ($isUncategorized): ?>
                        <div id="addcat-<?= $employee['id'] ?>" class="assign-popup">
                            <h3><i class="bi bi-folder-plus"></i> Add to Category</h3>
                            <form action="/hardwareTracker/employeeCategory" method="post" data-action="addCategory" data-employee="<?= htmlspecialchars($employeeName) ?>">
                                <label for="category">Select Category:</label>
                                <select name="category_id" required>
                                    <option value="" disabled selected>Choose a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <?php if ($category_id !== 1): ?>
                                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                <input type="hidden" name="operation" value="addEmployeeToCategory">
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
                                <?php endif; ?>

                <?php if (empty($employees)): ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h4>No Employees Yet</h4>
                        <p>Start by adding your first employee using the yellow button below!</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Fixed Add Button -->
        <form action="/hardwareTracker/employeeCategory" method="post">
            <input type="hidden" name="action" value="addEmployee">
            <input type="hidden" name="category_id" value="<?= $currentCategoryId ?>">
            <input type="hidden" name="isuncategorized" value="false">
            <button type="submit" class="btn-fixed-add">
                <i class="bi bi-person-plus"></i> Add Employee
            </button>
        </form>

    <!-- Backdrop -->
    <div class="backdrop" id="backdrop"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Details Popup
        function showDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "block";
            document.getElementById("backdrop").style.display = "block";
        }

        function closeDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Add to Category Popup - No alerts, just show
        function showAddCategory(employee_id) {
            document.getElementById("addcat-" + employee_id).style.display = "block";
            document.getElementById("backdrop").style.display = "block";
        }

        function closeAddCat(employee_id) {
            document.getElementById("addcat-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Uncategorize Modal
        function showUncategorizeModal(employee_id) {
            document.getElementById("uncategorize-" + employee_id).style.display = "block";
            document.getElementById("backdrop").style.display = "block";
        }

        function closeUncategorize(employee_id) {
            document.getElementById("uncategorize-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Backdrop click
        document.getElementById("backdrop").addEventListener("click", function() {
            document.querySelectorAll('.details-popup, .assign-popup').forEach(function(el) {
                el.style.display = "none";
            });
            this.style.display = "none";
        });

        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.details-popup, .assign-popup').forEach(function(el) {
                    el.style.display = "none";
                });
                document.getElementById("backdrop").style.display = "none";
            }
        });

        // Toast System
        var successMessages = {
            'edit': function(name) { return '<strong>' + name + '</strong> updated successfully!'; },
            'uncategorize': function(name) { return '<strong>' + name + '</strong> uncategorized successfully!'; },
            'addCategory': function(name) { return '<strong>' + name + '</strong> added to category successfully!'; }
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

        // Prevent resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>