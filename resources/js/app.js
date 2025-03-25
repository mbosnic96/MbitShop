import './bootstrap';
// resources/js/app.js
import Swal from 'sweetalert2';

window.Swal = Swal;  // Make SweetAlert2 globally available
import '@splidejs/splide/dist/css/splide.min.css'; // Core CSS

import Splide from '@splidejs/splide';
window.Splide = Splide; // <-- OVO je bitno


import './toastr'; // This will import toastr.js and make it available globally
import Alpine from 'alpinejs';

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

    window.openModal = function (modalId, data) {
        var modal = document.getElementById(modalId);
        var modalContent = modal.querySelector('.modal-content');
        if (modal && modalContent) {
            // Populate form fields dynamically
            Object.keys(data).forEach(key => {
                let inputField = modal.querySelector(`[name="${key}"]`);
                if (inputField) {
                    // Check if it's a select dropdown
                    if (inputField.tagName === 'SELECT') {
                        let option = inputField.querySelector(`option[value="${data[key]}"]`);
                        if (option) {
                            option.selected = true;
                        }
                    } else {
                        // For other input fields
                        inputField.value = data[key] ?? '';
                    }
                }
            });
    
            if (data.promo !== undefined) {
                const promoCheckbox = modal.querySelector('[name="promo"]');
                if (promoCheckbox) {
                    promoCheckbox.checked = Boolean(data.promo);
                    // Also update the hidden input
                    const hiddenPromo = modal.querySelector('input[name="promo"][type="hidden"]');
                    if (hiddenPromo) {
                        hiddenPromo.value = data.promo ? '0' : '1'; // Invert since checkbox will override
                    }
                }
            }
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
                    // Parse the JSON string or use as-is if already array
                    images = Array.isArray(data.image) ? data.image : JSON.parse(data.image);
                } catch (error) {
                    console.error('Error parsing images:', error);
                }
            
                const imageContainer = modal.querySelector('#uploaded-images');
                if (imageContainer) {
                    imageContainer.innerHTML = ''; // Clear existing images
                    
                    images.forEach((imagePath, index) => {
                        const imageWrapper = document.createElement('div');
                        imageWrapper.className = 'relative inline-block m-2';
                        
                        const imgElement = document.createElement('img');
                        imgElement.src = '/storage/' + imagePath;
                        imgElement.alt = 'Product image ' + (index + 1);
                        imgElement.className = 'w-24 h-24 object-cover rounded';
                        
                        const deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = '&times;';
                        deleteBtn.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700';
                        deleteBtn.title = 'Delete image';
                        deleteBtn.onclick = (e) => {
                            e.preventDefault();
                            deleteImage(imagePath, data.id, imageWrapper);
                        };
                        
                        imageWrapper.appendChild(imgElement);
                        imageWrapper.appendChild(deleteBtn);
                        imageContainer.appendChild(imageWrapper);
                    });
                }
            }

            function deleteImage(imagePath, productId, element) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = `/dashboard/products/${productId}/images`;
                        
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                image_path: imagePath
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                element.remove();
                                Swal.fire(
                                    'Deleted!',
                                    'Your image has been deleted.',
                                    'success'
                                );
                                
                                // Update the remaining images in the modal if needed
                                if (data.remaining_images && data.remaining_images.length === 0) {
                                    document.getElementById('uploaded-images').innerHTML = '';
                                }
                            } else {
                                throw new Error(data.message || 'Failed to delete image');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                error.message || 'Failed to delete image',
                                'error'
                            );
                        });
                    }
                });
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
