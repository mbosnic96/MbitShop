import './bootstrap';

// modal.js
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('customModal');
    var modalContent = document.querySelector('.modal-content');

    // Function to open the modal
    window.openModal = function () {
        if (modal && modalContent) {
            modal.classList.add('show');
            modalContent.classList.add('show');
            document.body.classList.add('modal-open');
            document.dispatchEvent(new Event('modalOpened'));
        }
    };

    // Function to close the modal
    window.closeModal = function () {
        if (modal && modalContent) {
            modal.classList.remove('show');
            modalContent.classList.remove('show');
            document.body.classList.remove('modal-open');
        }
    };

    // Close the modal if the overlay is clicked
    window.addEventListener('click', function (event) {
        if (modal && modalContent && event.target == modal) {
            closeModal();
        }
    });
});
