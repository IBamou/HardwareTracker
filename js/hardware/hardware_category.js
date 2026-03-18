        // ── Original Popup Logic (unchanged) ──────────────────
        function display(hardware_id, hasEmployees) {
            if (hasEmployees == 0) {
                document.getElementById("noticeEmployees").style.display = "block";
            } else {
                document.getElementById("assign-" + hardware_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }

        function closePopup(hardware_id) {
            if (hardware_id == 0) {
                document.getElementById("noticeEmployees").style.display = "none";
                document.getElementById("noticeCategories").style.display = "none";
            } else {
                document.getElementById("assign-" + hardware_id).style.display = "none";
            }
            document.getElementById("backdrop").style.display = "none";
        }

        function displayAddCat(hardware_id, categoryCount) {
            if (categoryCount == 0) {
                document.getElementById("noticeCategories").style.display = "block";
            } else {
                document.getElementById("addcat-" + hardware_id).style.display = "block";
            }
            document.getElementById("backdrop").style.display = "block";
        }

        function closeAddCat(hardware_id) {
            document.getElementById("addcat-" + hardware_id).style.display = "none";
            document.getElementById("backdrop").style.display = "none";
        }
        
        // ✨ NEW: Details Modal Functions
        function showDetails(hardware) {
            const modal = document.getElementById('detailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');

            // Set the title
            modalTitle.textContent = hardware.hardware_name + ' Details';
            
            // Clear previous details
            modalBody.innerHTML = '';
            
            // Define which details to show and their labels
            const detailsToShow = {
                'hardware_name': 'Name',
                'serial_number': 'Serial Number',
                'hardware_status': 'Status',
                'purchase_date': 'Purchase Date',
                'received_date': 'Received Date',
                'price': 'Price'
            };

            // Function to format the date nicely
            function formatDate(dateString) {
                if (!dateString || dateString === '0000-00-00') return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            }

            for (const key in detailsToShow) {
                if (hardware.hasOwnProperty(key)) {
                    let value = hardware[key];
                    
                    // Format dates
                    if (key === 'purchase_date' || key === 'received_date') {
                        value = formatDate(value);
                    }
                    
                    // Capitalize status
                    if (key === 'hardware_status') {
                        value = value.charAt(0).toUpperCase() + value.slice(1);
                    }

                    const item = document.createElement('div');
                    item.className = 'detail-item';
                    item.innerHTML = `<span class="detail-key">${detailsToShow[key]}</span> <span class="detail-value">${value || 'N/A'}</span>`;
                    modalBody.appendChild(item);
                }
            }

            modal.style.display = 'block';
            document.getElementById('backdrop').style.display = 'block';
        }

        function closeDetails() {
            document.getElementById('detailsModal').style.display = 'none';
            document.getElementById('backdrop').style.display = 'none';
        }

        // ✨ UPDATED: Generic close handlers for backdrop and Escape key
        document.getElementById("backdrop").addEventListener("click", function() {
            document.querySelectorAll('.assign-popup, #noticeEmployees, #noticeCategories, .details-modal').forEach(function(el) {
                el.style.display = "none";
            });
            this.style.display = "none";
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.assign-popup, #noticeEmployees, #noticeCategories, .details-modal').forEach(function(el) {
                    el.style.display = "none";
                });
                document.getElementById("backdrop").style.display = "none";
            }
        });

        // ── Success Toast System (unchanged) ──────────────────────────────
        var successMessages = {
            'edit':         function(name) { return '<strong>' + name + '</strong> updated successfully!'; },
            'assign':       function(name) { return '<strong>' + name + '</strong> assigned successfully!'; },
            'return':       function(name) { return '<strong>' + name + '</strong> returned successfully!'; },
            'uncategorize': function(name) { return '<strong>' + name + '</strong> uncategorized successfully!'; },
            'addCategory':  function(name) { return '<strong>' + name + '</strong> added to category successfully!'; }
        };

        var actionIcons = {
            'edit':         'pencil',
            'assign':       'person-check',
            'return':       'box-arrow-in-left',
            'uncategorize': 'folder-minus',
            'addCategory':  'folder-plus'
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

        // ── Store action info before form submits (unchanged) ─────────────
        document.querySelectorAll('form[data-action]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var action = this.getAttribute('data-action');
                var device = this.getAttribute('data-device');
                if (action && successMessages[action]) {
                    sessionStorage.setItem('toast_action', action);
                    sessionStorage.setItem('toast_device', device);
                }
            });
        });

        // ── Check for pending toast on page load (unchanged) ──────────────
        (function checkPendingToast() {
            var action = sessionStorage.getItem('toast_action');
            var device = sessionStorage.getItem('toast_device');
            if (action && successMessages[action]) {
                var message = successMessages[action](device || 'Device');
                var icon = actionIcons[action] || 'check-lg';
                showToast(message, icon);
            }
            sessionStorage.removeItem('toast_action');
            sessionStorage.removeItem('toast_device');
        })();

        // ── PHP-side uncategorize success (unchanged) ─────────────────────

        // ── Prevent form resubmission on refresh (unchanged) ──────────────
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }