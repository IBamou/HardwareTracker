        function showDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "block";
            document.getElementById("backdrop").style.display = "block";
        }

        function closeDetails(employee_id) {
            document.getElementById("details-" + employee_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }

        // Add to Category Popup - No alerts, just show
        function displayAddCat(employee_id, categoryCount) {
            if (categoryCount == 0) {
                document.getElementById("noticeCategories").style.display = "block";
            } else {
                document.getElementById("addcat-" + employee_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }


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