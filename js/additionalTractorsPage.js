function selectCapacity(value) {
    // Remove selected class from all capacity items
    const allItems = document.querySelectorAll('.capacity-item');
    allItems.forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to the clicked item
    const selectedItem = document.querySelector(`.capacity-item[onclick="selectCapacity('${value}')"]`);
    selectedItem.classList.add('selected');
    
    // Check the corresponding radio button
    const radio = selectedItem.querySelector('input[type="radio"]');
    radio.checked = true;
}

// Initialize by checking if any radio is already checked (on page load)
document.addEventListener('DOMContentLoaded', function() {
    const checkedRadio = document.querySelector('.capacity-radio:checked');
    if (checkedRadio) {
        const capacityItem = checkedRadio.closest('.capacity-item');
        capacityItem.classList.add('selected');
    }
});

function selectStatus(value) {
            // Remove selected class from all status options
            const allOptions = document.querySelectorAll('.status-option');
            allOptions.forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to the clicked option
            const selectedOption = document.querySelector(`.status-option[onclick="selectStatus('${value}')"]`);
            selectedOption.classList.add('selected');
            
            // Check the corresponding radio button
            const radio = selectedOption.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Hide error message if showing
            document.getElementById('statusError').classList.remove('show');
        }

        // Initialize by checking if any radio is already checked (on page load)
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('.capacity-radio:checked');
            if (checkedRadio) {
                const statusOption = checkedRadio.closest('.status-option');
                statusOption.classList.add('selected');
            }
            
            // Add form validation
            document.getElementById('tractorForm').addEventListener('submit', function(e) {
                const statusSelected = document.querySelector('input[name="status"]:checked');
                
                if (!statusSelected) {
                    e.preventDefault();
                    document.getElementById('statusError').classList.add('show');
                    
                    // Scroll to error message
                    document.getElementById('statusError').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center'
                    });
                }
            });
        });
