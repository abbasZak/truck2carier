// Quantity Adjustment
        let quantity = 1;
        let unit = 'tons';

        

        function adjustQuantity(change) {
            quantity += change;
            if (quantity < 1) quantity = 1;
            document.getElementById('quantityDisplay').textContent = quantity;
        }

        function selectUnit(selectedUnit) {
            unit = selectedUnit;
            const unitButtons = document.querySelectorAll('.unit-btn');
            unitButtons.forEach(button => {
                button.classList.remove('active');
                if (button.textContent.toLowerCase() === selectedUnit) {
                    button.classList.add('active');
                }
            });
        }

        // Form Submission
        document.getElementById('transportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Simulate form submission
            setTimeout(() => {
                // Hide loading overlay
                document.getElementById('loadingOverlay').style.display = 'none';

                // Show success message
                document.getElementById('successMessage').style.display = 'flex';

                // Reset form
                document.getElementById('transportForm').reset();

                // Reset quantity and unit
                quantity = 1;
                unit = 'tons';
                document.getElementById('quantityDisplay').textContent = quantity;
                const unitButtons = document.querySelectorAll('.unit-btn');
                unitButtons.forEach(button => button.classList.remove('active'));
                document.querySelector('.unit-btn').classList.add('active');

                // Hide success message after 5 seconds
                setTimeout(() => {
                    document.getElementById('successMessage').style.display = 'none';
                }, 5000);
            }, 2000);
        });

        // Filter Requests
        function filterRequests(status) {
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const requestCards = document.querySelectorAll('.request-card');
            requestCards.forEach(card => {
                if (status === 'all') {
                    card.style.display = 'block';
                } else {
                    card.style.display = card.getAttribute('data-status') === status ? 'block' : 'none';
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners or other initialization code here
        });