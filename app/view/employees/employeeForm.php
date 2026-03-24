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
    <title><?= $isEditing? 'Edit Employee' : 'Add Employee' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/employee/employeeForm.css">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-<?= $isEditing? 'pencil-square' : 'person-plus' ?>"></i>
                <?= $isEditing? 'Edit Employee' : 'Add New Employee' ?>
            </h1>
        </div>

        <div class="form-card">
            <form action="/hardwareTracker/employeeCategory" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="first_name">
                                <i class="bi bi-person"></i>First Name
                            </label>
                            <input type="text" 
                                   name="first_name" 
                                   id="first_name" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($first_name ?? '') ?>"
                                   placeholder="Enter first name"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="last_name">
                                <i class="bi bi-person-fill"></i>Last Name
                            </label>
                            <input type="text" 
                                   name="last_name" 
                                   id="last_name" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($last_name ?? '') ?>"
                                   placeholder="Enter last name"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="email">
                                <i class="bi bi-envelope"></i>Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($email ?? '') ?>"
                                   placeholder="Enter email address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="departement">
                                <i class="bi bi-building"></i>Department
                            </label>
                            <input type="text" 
                                   name="departement" 
                                   id="departement" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($departement ?? '') ?>"
                                   placeholder="Enter department">
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields - Preserved -->
                <input type="hidden" name="category_id" value="<?= htmlspecialchars($category_id ?? '') ?>">
                <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee_id ?? '') ?>">
                <input type="hidden" name="isuncategorized" value="<?= htmlspecialchars($isUncategorized ?? '') ?>">

                <div class="text-center">
                    <button type="submit" class="btn-submit" name="operation" value="<?= $isEditing ? 'editEmployee' : 'addEmployee' ?>">
                        <i class="bi bi-save"></i>
                        <?= $isEditing? 'Update Employee' : 'Save Employee' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="app/view/js/employee/employeeCategory.js"></script>
</body>
</html>