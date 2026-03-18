<?php


// Ensure variables are defined to avoid warnings
if (!isset($isEditing)) {
    $isEditing = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit Hardware' : 'Add Hardware' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            margin: 2rem 0 2rem;
        }

        .page-title {
            color: #333;
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .page-title i {
            color: #8b4513;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            max-width: 650px;
            margin: 0 auto 3rem;
            border-left: 4px solid #8b4513;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #a0522d;
            margin-right: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e8e0d5;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #faf8f5;
        }

        .form-control:focus {
            outline: none;
            border-color: #8b4513;
            background: white;
            box-shadow: 0 0 0 4px rgba(139, 69, 19, 0.1);
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%238b4513' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }

        .employee-section {
            background: #fff8e6;
            border: 2px solid #ffd700;
            border-radius: 10px;
            padding: 1.25rem;
            margin-top: 0.5rem;
            display: none;
            animation: slideDown 0.3s ease;
        }

        .employee-section.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-submit {
            background: #8b4513;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #654321;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .required {
            color: #e76f51;
            margin-left: 0.25rem;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-<?= $isEditing ? 'pencil-square' : 'plus-circle' ?>"></i>
                <?= $Readonly ? 'Hardware Details' : ($isEditing ? 'Edit Hardware' : 'Add New Hardware') ?>
            </h1>
        </div>

        <div class="form-card">
            <form action="/hardwareTracker/hardwareCategory" method="post">
                <div class="form-group">
                    <label class="form-label" for="hardware">
                        <i class="bi bi-cpu"></i>Hardware Name<span class="required"><?= !$Readonly ? '*' : '' ?></span>
                    </label>
                    <input type="text" 
                           id="hardware"
                           name="hardware" 
                           class="form-control" 
                           required 
                           value="<?= htmlspecialchars($hardwareName ?? '') ?>"
                           placeholder="Enter hardware name"
                           <?= $Readonly ? 'readonly' : '' ?>>
                    <input type="hidden" name="hardware_id" value="<?= htmlspecialchars($hardware_id) ?? '' ?>">
                    <input type="hidden" name="category_id" value="<?= htmlspecialchars($category_id) ?? '' ?>">
                    <input type="hidden" name="categorized" value="<?= htmlspecialchars($iscategorized) ?? '' ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="serial_number">
                        <i class="bi bi-upc-scan"></i>Serial Number
                    </label>
                    <input type="text" 
                           id="serial_number"
                           name="serial_number" 
                           class="form-control" 
                           value="<?= htmlspecialchars($serial_number ?? '') ?>"
                           placeholder="Enter serial number"
                           <?= $Readonly ? 'readonly' : '' ?>>

                </div>

                <div class="form-group">
                    <label class="form-label" for="status">
                        <i class="bi bi-info-circle"></i>Status
                    </label>
        <?php if ($Readonly): ?>
            <select name="" id="" class="form-control">
                <option type='text' value="<?= $status ?>" selected><?= $status ?> </option>
            </select>
            <?php if ($status == 'assigned'): ?>
                <label class="form-label" for="employee">
        <i class="bi bi-person-fill"></i>Assign to Employee
    </label>
            <select name="" id=""  class="form-control">
                            <option value="<?= $assignToEmployee['id'] ?>" selected>
                <?= htmlspecialchars($assignToEmployee['first_name'] . ' ' . $assignToEmployee['last_name']) ?>
            </option>
            </select>
            <?php endif; ?>

        



    <?php else: ?>
        <select name="status" id="status" class="form-control"></select>
        <option value="available" <?= (isset($status) && $status == 'available') ? 'selected' : '' ?>>Available</option>

        <option value="assigned" <?= (empty($employees) || (isset($status) && $status == 'assigned')) ? 'hidden' : '' ?>>Assigned</option>

        <option value="repair" <?= (isset($status) && $status == 'repair') ? 'selected' : '' ?>>Repair</option>

        <option value="retired" <?= (isset($status) && $status == 'retired') ? 'selected' : '' ?>>Retired</option>

    <?php endif; ?>
</select>
                </div>

<div id="employees" class="employee-section">
    <label class="form-label" for="employee">
        <i class="bi bi-person-fill"></i>Assign to Employee
    </label>

    <select name="employee_id" id="employee" class="form-control" <?= $Readonly ? 'disabled' : '' ?>>

        



            <option value="">Select an employee</option>

            <?php foreach($employees as $employee): ?>
                <option value="<?= $employee['id'] ?>"
                    <?= (isset($employee_id) && $employee_id == $employee['id']) ? 'selected' : '' ?>>
                    
                    <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                
                </option>
            <?php endforeach; ?>


    </select>
</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="purchase_date">
                                <i class="bi bi-calendar-check"></i>Purchase Date
                            </label>
                            <input type="date" 
                                   id="purchase_date"
                                   name="purchase_date" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($purchase_date ?? '') ?>"
                                   <?= $Readonly ? 'readonly' : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="received_date">
                                <i class="bi bi-calendar-plus"></i>Received Date
                            </label>
                            <input type="date" 
                                   id="received_date"
                                   name="received_date" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($received_date ?? '') ?>"
                                   <?= $Readonly ? 'readonly' : '' ?>>
                        </div>
                    </div>
                </div>
                    <div class="form-group">
        <label for="price">Price ($)</label>
        <input 
            type="number" 
            id="price" 
            name="price" 
            step="0.01" 
            min="0" 
            placeholder="0.00"
            required
            class="form-control"
            value="<?= htmlspecialchars($price ?? '') ?>"
            <?= $Readonly ? 'readonly' : '' ?>
        >
        <small class="text-muted">Enter price in dollars (e.g., 99.99)</small>
    </div>
                <!-- Basic HTML form -->
    <!-- Other fields -->

    <!-- Submit button -->
     <?php if(!$Readonly): ?>
                <div class="text-center">
                    <button type="submit" class="btn-submit" name="operation" value="<?= $isEditing ? 'editHardware' : 'addHardware' ?>">
                        <i class="bi bi-save"></i>
                        <?= $isEditing ? 'Update Hardware' : 'Save Hardware' ?>
                    </button>
                </div>
    <?php endif; ?>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = document.getElementById("status");
            const employeeSection = document.getElementById("employees");
            
            // Check initial state
            if (status.value === "assigned") {
                employeeSection.classList.add("show");
            }
            
            status.addEventListener("change", function() {
                if (this.value === "assigned") {
                    employeeSection.classList.add("show");
                } else {
                    employeeSection.classList.remove("show");
                }
            });
        });
    </script>
</body>
</html>