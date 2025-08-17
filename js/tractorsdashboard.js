// Global variables to store current request data
let currentRequestData = {};

// Show confirmation modal
function showConfirmAccept(requestId, produceType, quantity, pickupLocation, destination, urgencyLevel, farmerName) {
    // Store the request data globally
    currentRequestData = {
        id: requestId,
        produce: produceType,
        quantity: quantity,
        pickup: pickupLocation,
        destination: destination,
        urgency: urgencyLevel,
        farmer: farmerName
    };
    
    // Populate the modal with request details
    populateRequestPreview();
    
    // Show the modal
    const overlay = document.getElementById('confirmOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        overlay.classList.add('show');
        
        // Add event listeners for the buttons if they don't exist
        setupModalEventListeners();
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
}

// Hide confirmation modal
function hideConfirmAccept() {
    const overlay = document.getElementById('confirmOverlay');
    if (overlay) {
        overlay.classList.remove('show');
        
        // Add fade out animation
        overlay.style.animation = 'fadeOut 0.3s ease-out forwards';
        
        setTimeout(() => {
            overlay.style.display = 'none';
            overlay.style.animation = '';
            document.body.style.overflow = '';
        }, 300);
    }
    
    // Clear stored data
    currentRequestData = {};
}

// Populate the request preview in the modal
function populateRequestPreview() {
    const previewContainer = document.getElementById('requestPreview');
    if (!previewContainer) return;
    
    // Get urgency class for styling
    const urgencyClass = `urgency-${currentRequestData.urgency.toLowerCase()}`;
    
    previewContainer.innerHTML = `
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Farmer</div>
                <div class="preview-value">${currentRequestData.farmer}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Produce & Quantity</div>
                <div class="preview-value">${currentRequestData.produce} - ${currentRequestData.quantity}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Pickup Location</div>
                <div class="preview-value">${currentRequestData.pickup}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Destination</div>
                <div class="preview-value">${currentRequestData.destination}</div>
            </div>
        </div>
        
        <div class="preview-row">
            <div class="preview-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="preview-content">
                <div class="preview-label">Priority Level</div>
                <div class="preview-value ${urgencyClass}">${currentRequestData.urgency} Priority</div>
            </div>
        </div>
    `;
}

// Setup event listeners for modal buttons
function setupModalEventListeners() {
    const confirmBtn = document.getElementById('confirmAcceptBtn');
    const cancelBtn = document.querySelector('.btn-cancel');
    
    if (confirmBtn) {
        // Remove existing listeners to prevent duplicates
        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
        const newConfirmBtn = document.getElementById('confirmAcceptBtn');
        
        newConfirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            processAcceptRequest();
        });
    }
    
    if (cancelBtn) {
        // Remove existing listeners to prevent duplicates
        cancelBtn.replaceWith(cancelBtn.cloneNode(true));
        const newCancelBtn = document.querySelector('.btn-cancel');
        
        newCancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            hideConfirmAccept();
        });
    }
    
    // Close modal when clicking outside
    const overlay = document.getElementById('confirmOverlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                hideConfirmAccept();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay && overlay.style.display === 'flex') {
            hideConfirmAccept();
        }
    });
}

// Process the request acceptance
function processAcceptRequest() {
    if (!currentRequestData.id) {
        console.error('No request data available');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Create form data
    const formData = new FormData();
    formData.append('request_id', currentRequestData.id);
    formData.append('action', 'accept_request');
    
    // Send the request
    fetch('accept_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingState();
        
        if (data.success) {
            // Show success message
            showSuccessMessage('Request accepted successfully! The farmer will be notified.');
            
            // Hide the modal
            hideConfirmAccept();
            
            // Optional: Remove the accepted request from the list
            removeRequestFromList(currentRequestData.id);
            
            // Optional: Redirect or update UI
            // window.location.reload();
        } else {
            // Show error message
            showErrorMessage(data.message || 'Failed to accept request. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoadingState();
        showErrorMessage('Network error. Please check your connection and try again.');
    });
}

// Show loading state on buttons
function showLoadingState() {
    const confirmBtn = document.getElementById('confirmAcceptBtn');
    if (confirmBtn) {
        confirmBtn.classList.add('loading');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    }
    
    // Also show the main loading overlay if it exists
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
}

// Hide loading state
function hideLoadingState() {
    const confirmBtn = document.getElementById('confirmAcceptBtn');
    if (confirmBtn) {
        confirmBtn.classList.remove('loading');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-check"></i> Accept Request';
    }
    
    // Hide the main loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
}

// Show success message
function showSuccessMessage(message) {
    const successElement = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    if (successElement && successText) {
        successText.textContent = message;
        successElement.style.display = 'flex';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            successElement.style.display = 'none';
        }, 5000);
    }
}

// Show error message
function showErrorMessage(message) {
    // Create error message element if it doesn't exist
    let errorElement = document.getElementById('errorMessage');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'errorMessage';
        errorElement.className = 'error-message';
        errorElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span id="errorText"></span>';
        document.body.appendChild(errorElement);
        
        // Add CSS for error message
        const style = document.createElement('style');
        style.textContent = `
            .error-message {
                background: linear-gradient(135deg, #f44336, #d32f2f);
                color: white;
                padding: 1rem 2rem;
                border-radius: 15px;
                margin-bottom: 1rem;
                display: none;
                align-items: center;
                gap: 0.5rem;
                animation: slideDown 0.5s ease;
                position: fixed;
                top: 100px;
                right: 2rem;
                z-index: 9999;
                box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3);
            }
        `;
        document.head.appendChild(style);
    }
    
    const errorText = document.getElementById('errorText');
    if (errorText) {
        errorText.textContent = message;
        errorElement.style.display = 'flex';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            errorElement.style.display = 'none';
        }, 5000);
    }
}

// Remove request from the list (optional)
function removeRequestFromList(requestId) {
    const requestCard = document.querySelector(`[onclick*="acceptRequest(${requestId}"]`);
    if (requestCard) {
        const card = requestCard.closest('.request-card');
        if (card) {
            card.style.animation = 'fadeOut 0.5s ease-out forwards';
            setTimeout(() => {
                card.remove();
            }, 500);
        }
    }
}

// Filter Requests function (from your existing code)
function filterRequests(status) {
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    const requestCards = document.querySelectorAll('.request-card');
    requestCards.forEach(card => {
        if (status === 'all') {
            card.style.display = 'block';
        } else {
            const cardStatus = card.getAttribute('data-status');
            const cardLocation = card.getAttribute('data-location');
            const locationFilter = document.querySelector('.location-filter').value;

            let shouldShow = false;
            
            if (status === 'high') {
                shouldShow = cardStatus === 'high';
            } else if (status === 'nearby') {
                shouldShow = cardLocation === locationFilter || locationFilter === '';
            } else if (status === 'high-value') {
                // Add your logic for high-value requests
                shouldShow = cardStatus === 'high'; // placeholder
            } else {
                shouldShow = cardStatus === status;
            }

            card.style.display = shouldShow ? 'block' : 'none';
        }
    });
}

// Show Modal function (from your existing code)
function showModal(title) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('detailsModal').style.display = 'flex';
}

// Close Modal function (from your existing code)
function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Initialize everything when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tractor Dashboard initialized');
    
    // Setup any additional initialization here
    setupSearchFilters();
});

// Setup search and filter functionality
function setupSearchFilters() {
    const searchInput = document.querySelector('.search-input');
    const locationFilter = document.querySelector('.location-filter');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterRequestsBySearch(this.value);
        });
    }
    
    if (locationFilter) {
        locationFilter.addEventListener('change', function() {
            filterRequestsByLocation(this.value);
        });
    }
}

// Filter requests by search term
function filterRequestsBySearch(searchTerm) {
    const requestCards = document.querySelectorAll('.request-card');
    const term = searchTerm.toLowerCase();
    
    requestCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(term) ? 'block' : 'none';
    });
}

// Filter requests by location
function filterRequestsByLocation(location) {
    const requestCards = document.querySelectorAll('.request-card');
    
    requestCards.forEach(card => {
        const cardLocation = card.getAttribute('data-location');
        if (!location || cardLocation === location) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}