let currentRequestId = null;
let currentFarmerId = null;

// Updated Accept Request Function - gets data from parameters
function acceptRequest(requestId, produceType, quantity, pickupLocation, destination, urgencyLevel, farmerName, farmerId) {
    // Store all the data globally for later use
    currentRequestId = requestId;
    currentFarmerId = farmerId.trim(); // Store farmer ID and trim whitespace
    
    console.log('Accepting request:', {
        requestId: currentRequestId,
        farmerId: currentFarmerId,
        farmerName: farmerName
    });
    
    // Show confirmation popup with all the data
    showConfirmAccept(requestId, produceType, pickupLocation, destination, urgencyLevel, farmerName);
}

function showConfirmAccept(requestId, produce, pickup, destination, urgency, farmerName) {
    const preview = document.getElementById('requestPreview');
    const urgencyClass = `urgency-${urgency.toLowerCase()}`;
    
    preview.innerHTML = `
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Farmer</div>
                <div class="preview-value">${farmerName}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Produce</div>
                <div class="preview-value">${produce}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">From</div>
                <div class="preview-value">${pickup}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">To</div>
                <div class="preview-value">${destination}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon ${urgencyClass}">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Priority</div>
                <div class="preview-value">${urgency.charAt(0).toUpperCase() + urgency.slice(1)} Priority</div>
            </div>
        </div>
    `;
    
    document.getElementById('confirmOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

// FIXED: Single function to handle the actual acceptance
function processAcceptRequest() {
    const btn = document.getElementById('confirmAcceptBtn');
    btn.disabled = true;
    btn.innerHTML = '<div class="loading-spinner"></div> Processing...';
    
    // Make sure we have both IDs
    if (!currentRequestId || !currentFarmerId) {
        showErrorMessage('Missing request or farmer information. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Accept Request';
        return;
    }
    
    console.log('Sending request with:', {
        requestId: currentRequestId,
        farmerId: currentFarmerId
    });

    fetch('accept_request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `requestId=${encodeURIComponent(currentRequestId)}&farmerId=${encodeURIComponent(currentFarmerId)}`
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessMessage(data.message);
            hideConfirmAccept();
            // Update UI or reload to reflect the new status
            setTimeout(() => {
                location.reload(); // Reload to show updated status
            }, 1500);
        } else {
            showErrorMessage(data.message || 'Server reported failure');
            console.error('Server error:', data);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showErrorMessage(`Error: ${error.message}`);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Accept Request';
    });
}

function hideConfirmAccept() {
    document.getElementById('confirmOverlay').classList.remove('show');
    document.body.style.overflow = 'auto';
    currentRequestId = null;
    currentFarmerId = null;
    
    const btn = document.getElementById('confirmAcceptBtn');
    btn.classList.remove('loading');
    btn.innerHTML = '<i class="fas fa-check"></i> Accept Request';
    btn.disabled = false;
}

function closeConfirmOverlay() {
    document.getElementById('confirmOverlay').classList.remove('show');
    document.body.style.overflow = '';
    // Clear the stored IDs
    currentRequestId = null;
    currentFarmerId = null;
}

function showSuccessMessage(message) {
    // Create and show success notification
    const notification = document.createElement('div');
    notification.className = 'notification success-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                z-index: 10001;
                animation: slideInRight 0.3s ease-out;
                max-width: 400px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }
            
            .success-notification {
                background: linear-gradient(135deg, #4CAF50, #45a049);
            }
            
            .error-notification {
                background: linear-gradient(135deg, #f44336, #d32f2f);
            }
            
            .notification-content {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            .notification-content i {
                font-size: 20px;
            }
            
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            @keyframes fadeOut {
                from { opacity: 1; transform: scale(1); }
                to { opacity: 0; transform: scale(0.95); }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

function showErrorMessage(message) {
    // Create and show error notification
    const notification = document.createElement('div');
    notification.className = 'notification error-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const overlay = document.getElementById('confirmOverlay');
    if (e.target === overlay) {
        hideConfirmAccept();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideConfirmAccept();
    }
});

function updateRequestDisplay(requestId) {
    // Find the request card by ID and update or remove it
    const requestCard = document.querySelector(`.request-card[data-id="${requestId}"]`);
    if (requestCard) {
        // Either remove the card or update its status visually
        requestCard.style.display = 'none';
    }
}

// Prevent modal from closing when clicking inside the modal
document.addEventListener('click', function(e) {
    if (e.target.closest('.confirm-modal')) {
        e.stopPropagation();
    }
});