import './bootstrap';
// resources/js/app.js
import Swal from 'sweetalert2';

window.Swal = Swal;  // Make SweetAlert2 globally available

import Alpine from 'alpinejs';
import './toastr'; // This will import toastr.js and make it available globally


document.addEventListener('DOMContentLoaded', function () {
    
    // Listen for button clicks with the class 'open-modal'
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = button.getAttribute('data-modal-id');  // Get modal ID from button
            const productData = JSON.parse(this.getAttribute('data-all')) ?? ''; // Parse JSON
            console.log(productData);

            
            openModal(modalId, productData);
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
                    inputField.value = data[key] ?? '';  
                }
            });
    
            // Handle brand and category fields
            if (data.brand || data.category) {
                if (data.brand) {
                    const brandSelect = modal.querySelector('[name="brand"]');
                    if (brandSelect) {
                        brandSelect.value = data.brand.id; // Dynamically set the brand ID
                    }
                }
    
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
            }
    
            // Handle images
            if (data.image) {
                let images = [];
                try {
                    images = JSON.parse(data.image);
                } catch (error) {
                    console.error('Error parsing images:', error);
                }
    
                const imageContainer = modal.querySelector('#uploaded-images');
                if (imageContainer) {
    
                    images.forEach(image => {
                        const imgElement = document.createElement('img');
                        imgElement.src = '/storage/' + image; 
                        imgElement.alt = image;
                        imgElement.classList.add('w-16', 'h-16', 'object-cover', 'mt-2');
                        imageContainer.appendChild(imgElement);
                    });
                }
            }
    
            // Update form action dynamically for editing
            let form = modal.querySelector('form');
            if (form) {
                if (data.id) {
                    form.action = form.action.replace(':id', data.id);
                } else {
                    form.action = form.action.replace(':id', '');
                }
    
               
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent default form submission
    
                    const formData = new FormData(form);
                    const url = form.getAttribute('action');
    
                    axios.post(url, formData)
                        .then(response => {
                            // Show success toastr message
                            toastr.success(response.data.message, {
                                closeButton: true,
                                progressBar: true,
                                positionClass: 'toast-top-right',
                            });
    
                            closeModal(modalId);
                            
                        })
                        .catch(error => {
                            // Show error toastr message
                            toastr.error(error.response?.data?.message || 'Desila se greška!', 'Error!', {
                                closeButton: true,
                                progressBar: true,
                                positionClass: 'toast-top-right',
                            });
                        });
                });
                
            }
    
            // Show the modal
            modal.classList.add('show');
            modalContent.classList.add('show');
            document.body.classList.add('modal-open');
        } else {
            console.error('Modal or modal content not found!');
        }
    };

    // Close modal event listener
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = button.getAttribute('data-modal'); 
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
                } else {
                    console.error('Hidden input for category not found!');
                }
            }
        });
    });
    
    
   
});
