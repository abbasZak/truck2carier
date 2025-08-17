let currentRequestId = null;

// Updated Accept Request Function - gets data from DOM
function acceptRequest(requestId) {
    // Find the request card that contains this button
    const requestCard = event.target.closest('.request-card');
    
    if (!requestCard) {
        alert('Could not find request details');
        return;
    }
    
    // Extract data from the DOM
    const produceElement = requestCard.querySelector('.detail-content p');
    const produce = produceElement ? produceElement.textContent : 'Unknown';
    
    const pickupElement = requestCard.querySelector('.detail-item:nth-child(2) .detail-content p');
    const pickup = pickupElement ? pickupElement.textContent : 'Unknown';
    
    const destinationElement = requestCard.querySelector('.detail-item:nth-child(3) .detail-content p');
    const destination = destinationElement ? destinationElement.textContent : 'Unknown';
    
    const urgencyElement = requestCard.querySelector('.urgency-badge');
    const urgencyText = urgencyElement ? urgencyElement.textContent.replace(' Priority', '') : 'Unknown';
    const urgency = urgencyText.toLowerCase();
    
    const farmerElement = requestCard.querySelector('.farmer-info span');
    const farmerName = farmerElement ? farmerElement.textContent : 'Unknown';
    
    // Show confirmation popup
    showConfirmAccept(requestId, produce, pickup, destination, urgency, farmerName);
}

function showConfirmAccept(requestId, produce, pickup, destination, urgency, farmerName) {
    currentRequestId = requestId;
    
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

function hideConfirmAccept() {
    document.getElementById('confirmOverlay').classList.remove('show');
    document.body.style.overflow = 'auto';
    currentRequestId = null;
    
    const btn = document.getElementById('confirmAcceptBtn');
    btn.classList.remove('loading');
    btn.innerHTML = '<i class="fas fa-check"></i> Accept Request';
    btn.disabled = false;
}

function processAcceptRequest() {
    if (!currentRequestId) {
        console.error('No request ID set');
        return;
    }

    const btn = document.getElementById('confirmAcceptBtn');
    btn.disabled = true;
    btn.innerHTML = '<div class="loading-spinner"></div> Processing...';

    console.log('Sending request for ID:', currentRequestId);

    fetch('accept_request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `requestId=${encodeURIComponent(currentRequestId)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessMessage(data.message);
            // Update UI or reload
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
        
        // OR update status visually if you want to keep it visible
        // const statusElement = requestCard.querySelector('.request-status');
        // if (statusElement) {
        //     statusElement.textContent = 'Accepted';
        //     statusElement.classList.add('status-accepted');
        // }
    }
}

// Prevent modal from closing when clicking inside the modal
document.addEventListener('click', function(e) {
    if (e.target.closest('.confirm-modal')) {
        e.stopPropagation();
    }
});