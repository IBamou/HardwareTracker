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
    <link rel="stylesheet" href="app/view/css/hardware/hardwareCategories.css">
</head>
<body>

<main class="container-lg">
    <!-- Added: Success Message -->
    <?php if ($deleteSuccess): ?>
        <div class="success-message">
            <i class="bi bi-check-circle me-2"></i>
            Category deleted successfully! All associated devices have been uncategorized.
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
                    
                    <div class="category-card">
                        <div class="category-name">
                            <i class="bi bi-cpu"></i>
                            <?= htmlspecialchars($category['name']) ?>
                        </div>

                        <div class="action-buttons">
                            <!-- Show Button -->
                            <form action="/hardwareTracker/hardwareCategory" method="post">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($category['id'])?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($category['name'])?>">
                                <input type="hidden" name="description" value="<?= htmlspecialchars($category['description'])?>">
                                <button type="submit" name="showCategory" value="show" class="btn-show">
                                    <i class="bi bi-eye"> View </i> 
                                </button>
                            </form>

                            <?php if($category['id'] != 1): ?>
                                <!-- Edit Button -->
                                <form action="/hardwareTracker/hardwares" method="post">
                                    <input type="hidden" name="turn_off_search" value="true">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($category['id'])?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($category['name'])?>">
                                    <input type="hidden" name="description" value="<?= htmlspecialchars($category['description'])?>">
                                    <button type="submit" name="action" value="editHardwareCategory" class="btn-edit">
                                        <i class="bi bi-pencil"> Edit </i>
                                    </button>
                                </form>

                                <!-- Delete Button - Trigger Modal -->
                                <button type="button" class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?= htmlspecialchars($category['id'])?>">
                                    <i class="bi bi-trash"> Delete </i> 
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal<?= htmlspecialchars($category['id']) ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= htmlspecialchars($category['id']) ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?= htmlspecialchars($category['name']) ?>">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the category <strong><?= htmlspecialchars($category['name']) ?></strong>?</p>
                                    <!-- Added: Modal Notice About Uncategorized Elements -->
                                    <div class="modal-notice">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        All devices in this category will be moved to the "Uncategorized" section.
                                    </div>
                                    <p class="text-danger small mt-2">This action cannot be undone!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="/hardwareTracker/hardwares" method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
                                        <button type="submit" name="operation" value="deleteHardwareCategory" class="btn btn-danger">
                                            <i class="bi bi-trash">  Delete Category </i>
                                        </button>
                                    </form>
                                </div>
                            </div>  
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (count($categories) == 1 && !$inSreach) :?>
                <div class="empty-state">
                    <i class="bi bi-folder-open">
                        <h4>No Categories Yet</h4>
                        <p>Start by adding your first category using the yellow buttons on the side!</p>    
                    </i>            
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Fixed Add Buttons -->
<div class="fixed-add-buttons">
    <form action="/hardwareTracker/hardwares" method="post">
        <input type="hidden" name="turn_off_search" value="true">
        <button type="submit" class="btn-fixed-add" name="action" value="addHardwareCategory">
            <i class="bi bi-plus-circle"> Add A Category </i> 
        </button>
    </form>

    <form action="/hardwareTracker/hardwareCategory" method="post">
        <input type="hidden" name="action" value="addHardware">
        <input type="hidden" name="turn_off_search" value="true">
        <input type="hidden" name="isuncategorized" value="<?= $isUncategorized ?>">
        <input type="hidden" name="category_id" value="<?= $currentCategoryId ?>">
        <button type="submit" class="btn-fixed-add"><i class="bi bi-plus-circle"> Add A Device </i></button>
    </form>
</div>

<script src="app/view/bootstrap-5.3.8-dist/js/bootstrap.min.js"></script>
</body>
</html>