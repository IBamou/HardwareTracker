<?php 


$deleteSuccess = false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/view/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/view/css/employee/employeeCategories.css">
</head>
<body>

<main class="container-lg">
    <!-- Added: Success Message -->
    <?php if ($deleteSuccess): ?>
        <div class="success-message">
            <i class="bi bi-check-circle me-2">
                Category deleted successfully! All associated employees have been uncategorized.
            </i> 
        </div>
    <?php endif; ?>
    
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-box-seam">
                <?= htmlspecialchars($title) ?>
            </i>   
        </h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            <?php if(!empty($categories)): ?>

                <?php foreach($categories as $category): ?>
                    <?php $categoryName = $category['name']; ?>
                    <?php $categoryId = $category['id']; ?>
                    <?php $categoryDescription = $category['description']; ?>

                    <div class="category-card">
                        <div class="category-name">
                            <i class="bi bi-cpu"></i>
                            <?= htmlspecialchars($categoryName) ?>
                        </div>

                        <div class="action-buttons">
                            <!-- Show Button -->
                            <form action="/hardwareTracker/employeeCategory" method="post">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($categoryId)?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($categoryName)?>">
                                <input type="hidden" name="description" value="<?= htmlspecialchars($categoryDescription)?>">
                                <button type="submit" name="showCategory" value="show" class="btn-show">
                                    <i class="bi bi-eye">
                                        View
                                    </i>
                                </button>
                            </form>

                            <?php if($categoryId != 1 && $categoryId != 2): ?>
                                <!-- Edit Button -->
                                <form action="/hardwareTracker/employees" method="post">
                                    <input type="hidden" name="turn_off_search" value="true">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($categoryId) ?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($categoryName) ?>">
                                    <input type="hidden" name="description" value="<?= htmlspecialchars($categoryDescription) ?>">
                                    <button type="submit" name="action" value="editEmployeeCategory" class="btn-edit">
                                        <i class="bi bi-pencil">
                                            Edit
                                        </i> 
                                    </button>
                                </form>

                                <!-- Delete Button - Trigger Modal -->
                                <button type="button" class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?= htmlspecialchars($categoryId)?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal<?= htmlspecialchars($categoryId) ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= htmlspecialchars($categoryId) ?>" aria-hidden="true" style="display:none" >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?= htmlspecialchars($categoryName) ?>">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the category <strong><?= htmlspecialchars($categoryName) ?></strong>?</p>
                                    <!-- Added: Modal Notice About Uncategorized Elements -->
                                    <div class="modal-notice">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        All employees in this category will be moved to the "Uncategorized" section.
                                    </div>
                                    <p class="text-danger small mt-2">This action cannot be undone!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="/hardwareTracker/employees" method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($categoryId) ?>">
                                        <button type="submit" name="operation" value="deleteEmployeeCategory" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Delete Category
                                        </button>
                                    </form>
                                </div>
                            </div>  
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <?php if (count($categories) == 2) :?>
                <div class="empty-state">
                    <i class="bi bi-folder-open"></i>
                    <h4>No Categories Yet</h4>
                    <p>Start by adding your first category using the yellow buttons on the side!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Fixed Add Buttons -->
<div class="fixed-add-buttons">
    <form action="/hardwareTracker/employees" method="post">
        <input type="hidden" name="turn_off_search" value="true">
        <button type="submit" class="btn-fixed-add" name="action" value="addEmployeeCategory">
            <i class="bi bi-plus-circle"></i> Add A Category
        </button>
    </form>

    
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
<script src="app/view/bootstrap-5.3.8-dist/js/bootstrap.min.js"></script>
</body>
</html>