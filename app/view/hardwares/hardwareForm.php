<?php


// Ensure variables are defined to avoid warnings
if (!isset($isEditing)) {
    $isEditing = false;
}
if (!isset($Readonly)) {
    $Readonly = false;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit Hardware' : 'Add Hardware' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/hardware/hardwareForm.css">
    <style>
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
                    <input type="hidden" name="hardware_id" value="<?= htmlspecialchars($hardware_id  ?? '' )?>">
                    <input type="hidden" name="category_id" value="<?= htmlspecialchars($category_id ?? '' ) ?>">
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
                           <?= $Readonly ? 'readonly' : '' ?>
                    >
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
                        <select name="status" id="status" class="form-control">
                            <option value="available" <?= (isset($status) && $status == 'available') ? 'selected' : '' ?>>Available</option>
                            <option value="assigned" <?= (empty($employees) || (isset($status) && $status == 'assigned') || empty(array_filter($employees, function($employee) {return $employee['status']  == 'active';}))) ? 'hidden' : '' ?>>Assigned</option>
                            <option value="repair" <?= (isset($status) && $status == 'repair') ? 'selected' : '' ?>>Repair</option>
                            <option value="retired" <?= (isset($status) && $status == 'retired') ? 'selected' : '' ?>>Retired</option>
                        </select>
                    <?php endif; ?>     
        </div>
        <div id="employees" class="employee-section">
            <label class="form-label" for="employee">
                <i class="bi bi-person-fill"></i>Assign to Employee
            </label>

            <select  class="form-control" name="employee_id" id="employee" class="form-control" <?= $Readonly ? 'disabled' : '' ?>>
                <option value="">Select an employee</option>

                <?php foreach($employees as $employee): ?>
                    <?php if ($employee['status'] == 'active') :?>
                        <option value="<?= $employee['id'] ?>" <?= (isset($employee_id) && $employee_id == $employee['id']) ? 'selected' : '' ?>>     
                            <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                        </option>
                    <?php endif ?>
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