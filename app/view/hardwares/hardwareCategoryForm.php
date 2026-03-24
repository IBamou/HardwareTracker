<?php

// Determine if editing or adding based on session value
// $categoryName = $_SESSION['category'] ?? '';
// $isEditing = !empty($categoryName);
if (!isset($isEditing)) {$isEditing = false;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit Category' : 'Add Category' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/hardware/hardwareCategoryForm.css">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-<?= $isEditing ? 'pencil-square' : 'folder-plus' ?>"></i>
                <?= $isEditing ? 'Edit Device Category' : 'Add New Device Category' ?>
            </h1>
        </div>

        <div class="form-card">
            <form action="/hardwareTracker/hardwares" method="post">
                <div class="form-group">
                    <label class="form-label" for="catname">
                        <i class="bi bi-tag"></i>Category Name
                    </label>
                    <input type="text"
                           id="catname"
                           name="name"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($categoryName ?? '') ?>"
                           placeholder="Enter category name">
                </div>

                <div class="form-group">
                    <label class="form-label" for="catdesc">
                        <i class="bi bi-card-text"></i>Category Description
                    </label>
                    <input type="text"
                           id="catdesc"
                           name="description"
                           class="form-control"
                           value="<?= htmlspecialchars($categoryDescription ?? '')  ?>"
                           placeholder="Enter category description (optional)">
                </div>
                <input type="hidden" name="id" value="<?=htmlspecialchars($categoryId ?? '') ?>">
                <div class="text-center">
                    <button type="submit" class="btn-submit" name="operation" value="<?= $isEditing ? 'editHardwareCategory' : 'addHardwareCategory' ?>">
                        <i class="bi bi-save"></i>
                        <?= $isEditing ? 'Update Category' : 'Save Category' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="app/view/bootstrap-5.3.8-dist/js/bootstrap.min.css"></script>
</body>
</html>