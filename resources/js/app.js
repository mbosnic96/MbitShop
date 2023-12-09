import './bootstrap';

// modal.js
document.addEventListener('DOMContentLoaded', function () {
    // Function to open the modal
    window.openModal = function (modalId) {
        var modal = document.getElementById(modalId);
        var modalContent = modal.querySelector('.modal-content');

        if (modal && modalContent) {
            modal.classList.add('show');
            modalContent.classList.add('show');
            document.body.classList.add('modal-open');
        }
    };

    // Function to close the modal
    window.closeModal = function (modalId) {
        var modal = document.getElementById(modalId);
        var modalContent = modal.querySelector('.modal-content');

        if (modal && modalContent) {
            modal.classList.remove('show');
            modalContent.classList.remove('show');
            document.body.classList.remove('modal-open');
        }
    };

    // Close the modal if the overlay is clicked
    window.addEventListener('click', function (event) {
        var modalId = event.target.dataset.modalId;

        if (modalId) {
            closeModal(modalId);
        }
    });
});
