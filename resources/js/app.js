import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    
    // Listen for button clicks with the class 'open-modal'
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = button.getAttribute('data-modal-id');  // Get modal ID from button
            const productData = JSON.parse(this.getAttribute('data-all')) ?? ''; // Parse JSON
            console.log(productData);

            // Example: Show the brand and category in the modal
            openModal(modalId, productData); // Open modal with full product data
        });
    });

    // Function to open the modal
    window.openModal = function (modalId, data) {
        var modal = document.getElementById(modalId);
        var modalContent = modal.querySelector('.modal-content');
        if (modal && modalContent) {
            // Populate form fields dynamically
            Object.keys(data).forEach(key => {
                let inputField = modal.querySelector(`[name="${key}"]`);
                if (inputField) {
                    inputField.value = data[key] ?? '';  // Set value for each form field
                }
            });
            if(data.brand || data.category){
            if (data.brand) {
                const brandSelect = modal.querySelector('[name="brand"]');
                if (brandSelect) {
                    // Set the selected option for the brand
                    brandSelect.value = data.brand.id; // Dynamically set the brand ID
                }
            }
    
            // Dynamically populate the 'category' field if 'category' exists in data
            if (data.category) {
                const categoryInput = modal.querySelector('[name="category"]');
                const categoryDisplay = modal.querySelector('.dropdown-header span');

                if (categoryInput) {
                    categoryInput.value = data.category.id;
                }
                if (categoryDisplay) {
                    categoryDisplay.textContent = data.category.name;
                }
            }

            const images = JSON.parse(data.image);
            console.log(images);
            // Get the container to display images
            const imageContainer = document.getElementById('uploaded-images');
            
            // Dynamically create <img> elements for each image
            images.forEach(image => {
                const imgElement = document.createElement('img');
                imgElement.src = '/storage/' + image;  // Assuming images are stored in the public disk
                imgElement.alt = image;
                imgElement.classList.add('h-50', 'max-w-full', 'rounded-lg');
                imageContainer.appendChild(imgElement);
            });
        }
    

            // Update form action dynamically for editing
            let form = modal.querySelector('form');
            if (form && data.id) {
                let actionUrl = form.getAttribute('action').replace(':id', data.id);
                form.setAttribute('action', actionUrl);
            } else {
                form.setAttribute('action', form.getAttribute('action').replace(':id', ''));
            }

            modal.classList.add('show');
            modalContent.classList.add('show');
            document.body.classList.add('modal-open');
        }
    };

    // Close modal event listener
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = button.getAttribute('data-modal');  // Get modal ID from button
            closeModal(modalId);
        });
    });

    // Function to close the modal
    window.closeModal = function (modalId) {
        var modal = document.getElementById(modalId);
        var modalContent = modal.querySelector('.modal-content');

        if (modal && modalContent) {
            modal.classList.remove('show');
            modalContent.classList.remove('show');
            document.body.classList.remove('modal-open');

            // Clear modal content on close
            clearModal(modal);
        }
    };

    // Function to clear modal data (reset fields and remove images)
    function clearModal(modal) {
        // Clear all input fields in the modal
        modal.querySelectorAll('input, select').forEach(input => {
            input.value = ''; // Reset field value
        });

        // Clear any dynamically added images
        const imageContainer = modal.querySelector('#uploaded-images');
        if (imageContainer) {
            imageContainer.innerHTML = ''; // Remove all images from container
        }
    }

    // Close modal event listener
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = button.getAttribute('data-modal');  // Get modal ID from button
            closeModal(modalId);
        });
    });

    
    // Auto-hide session message after 3 seconds
    const message = document.getElementById('session-message');
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 3000);
    }


    

    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const dropdownHeader = dropdown.querySelector('.dropdown-header');
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        const categoryInput = dropdown.closest('form').querySelector('input[name="category"]');
    
        // Toggle dropdown visibility
        dropdownHeader.addEventListener('click', function () {
            dropdown.classList.toggle('active');
        });
    
        // Handle dropdown interactions
        dropdownContent.addEventListener('click', function (e) {
            if (e.target.classList.contains('toggle')) {
                const parentCategory = e.target.closest('.parent-category');
                parentCategory.classList.toggle('active');
                const childCategories = parentCategory.querySelector('.child-categories');
                childCategories.style.display = childCategories.style.display === 'block' ? 'none' : 'block';
            }
    
            // Handle child category selection
            if (e.target.classList.contains('child-category')) {
                const selectedValue = e.target.getAttribute('data-value');
                const selectedText = e.target.textContent;
    
                dropdownHeader.querySelector('span').textContent = selectedText;
                dropdown.classList.remove('active');
    
                // Ensure hidden input field gets updated correctly
                if (categoryInput) {
                    categoryInput.value = selectedValue;
                    console.log('Updated Category:', categoryInput.value); // Debugging
                } else {
                    console.error('Hidden input for category not found!');
                }
            }
        });
    });
    

    document.querySelector('form').addEventListener('submit', function (e) {
        const categoryInput = this.querySelector('input[name="category"]');
        console.log('Final Category Value Before Submission:', categoryInput.value);
    });
    
    
});
