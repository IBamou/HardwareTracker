<?php


// Determine if editing or adding based on session value
if (!isset($isEditing)) {
    $isEditing = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit Category' : 'Add Category' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/employee/employeeCategoryForm.css">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-<?= $isEditing ? 'pencil-square' : 'folder-plus' ?>"></i>
                <?= $isEditing ? 'Edit Employee Category' : 'Add New Employee Category' ?>
            </h1>
        </div>

        <div class="form-card">
            <form action="/hardwareTracker/employees" method="post">
                <div class="form-group">
                    <label class="form-label" for="employee_category_name">
                        <i class="bi bi-tag"></i>Employee Category Name
                    </label>
                    <input type="text"
                           id="employee_category_name"
                           name="name"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($categoryName ?? '') ?>"
                           placeholder="Enter category name">
                </div>
                               <div class="form-group">
                    <label class="form-label" for="employee_category_description">
                        <i class="bi bi-tag"></i>Employee Category Description
                    </label>
                    <input type="text"
                           id="employee_category_description"
                           name="description"
                           class="form-control"
                           required
                                    value="<?= htmlspecialchars($categoryDescription ?? '') ?>"
                           placeholder="Enter category description">
                </div>
                <input type="hidden" name="id" value="<?= htmlspecialchars($categoryId ?? '') ?>">
                <div class="text-center">
                    <button type="submit" class="btn-submit" name="operation" value="<?= $isEditing ? 'editEmployeeCategory' : 'addEmployeeCategory' ?>">
                        <i class="bi bi-save"></i>
                        <?= $isEditing ? 'Update Category' : 'Save Category' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="app/view/js/employee/employeeCategory.js"></script>
</body>
</html>