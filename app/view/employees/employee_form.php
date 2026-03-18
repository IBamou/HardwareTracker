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
    <link rel="stylesheet" href="http://localhost/hardwareTracker/css/employee/employee_form.css">
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
            max-width: 700px;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>