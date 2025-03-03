// resources/js/toastr.js
import toastr from 'toastr';
import 'toastr/build/toastr.min.css'; // Import the Toastr CSS

// Optionally, set global Toastr options here
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000", // 5 seconds
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

// Export Toastr so it can be used globally
window.toastr = toastr;
