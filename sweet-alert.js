// Function to show success message
function showSuccess(message) {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
}

// Function to show error message
function showError(message) {
    Swal.fire({
        title: 'Error!',
        text: message,
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}

// Function to show delete confirmation
function confirmDelete(formId) {
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
            document.getElementById(formId).submit();
        }
    });
    return false;
}

// Check for session message on page load
document.addEventListener('DOMContentLoaded', function() {
    const messageElement = document.getElementById('sweet-alert-message');
    if (messageElement) {
        const message = messageElement.getAttribute('data-message');
        const type = messageElement.getAttribute('data-type');
        if (type === 'success') {
            showSuccess(message);
        } else {
            showError(message);
        }
    }
});