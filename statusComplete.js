document.addEventListener('DOMContentLoaded', function() {
    // Handle delivered button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delivered')) {
            const button = e.target.closest('.btn-delivered');
            const requestId = button.getAttribute('data-requestId');
            
            // Disable button to prevent multiple clicks
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Send request to update status
            fetch('update_delivery_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    request_id: requestId,
                    status: 'completed'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success popup
                    showDeliverySuccessPopup();
                    
                    // Update the request card UI
                    updateRequestCardStatus(button, requestId);
                    
                } else {
                    // Show error message
                    showMessage('Error: ' + (data.message || 'Failed to update delivery status'), 'error');
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-check-circle"></i> Delivered';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Network error. Please try again.', 'error');
                
                // Reset button
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check-circle"></i> Delivered';
            });
        }
    });
});

function showDeliverySuccessPopup() {
    // Create popup HTML
    const popupHTML = `
        <div class="delivery-popup-overlay" id="deliveryPopup">
            <div class="delivery-popup">
                <div class="popup-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="popup-content">
                    <h2>Delivery Confirmed!</h2>
                    <p>Thanks for using <strong>Truck2Carrier</strong></p>
                    <div class="popup-decoration">
                        <div class="confetti"></div>
                        <div class="confetti"></div>
                        <div class="confetti"></div>
                        <div class="confetti"></div>
                        <div class="confetti"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add popup to body
    document.body.insertAdjacentHTML('beforeend', popupHTML);
    
    // Trigger animation
    const popup = document.getElementById('deliveryPopup');
    setTimeout(() => {
        popup.classList.add('show');
    }, 10);
    
    // Remove popup after 2 seconds
    setTimeout(() => {
        popup.classList.add('hide');
        setTimeout(() => {
            popup.remove();
        }, 500);
    }, 2000);
}

function updateRequestCardStatus(button, requestId) {
    // Find the request card
    const requestCard = button.closest('.request-card');
    
    if (requestCard) {
        // Update status badge
        const statusBadge = requestCard.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.textContent = 'completed';
            statusBadge.className = 'status-badge status-completed';
        }
        
        // Update card data attribute
        requestCard.setAttribute('data-status', 'completed');
        
        // Remove the delivered button and replace with completed indicator
        const actionsContainer = button.parentElement;
        button.remove();
        
        // Add completed indicator
        const completedIndicator = document.createElement('div');
        completedIndicator.className = 'completed-indicator';
        completedIndicator.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>Completed</span>
        `;
        actionsContainer.insertBefore(completedIndicator, actionsContainer.firstChild);
    }
}

function showMessage(message, type = 'success') {
    const messageBox = document.getElementById('formMessage');
    const messageIcon = document.getElementById('messageIcon');
    const messageText = document.getElementById('messageText');
    
    // Set message content
    messageText.textContent = message;
    
    // Set icon and class based on type
    if (type === 'success') {
        messageIcon.className = 'fas fa-check-circle';
        messageBox.className = 'message-box success';
    } else {
        messageIcon.className = 'fas fa-exclamation-triangle';
        messageBox.className = 'message-box error';
    }
    
    // Show message
    messageBox.classList.remove('hidden');
    
    // Hide after 3 seconds
    setTimeout(() => {
        messageBox.classList.add('hidden');
    }, 3000);
}