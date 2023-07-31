document.addEventListener('DOMContentLoaded', function() {
    // Find the status message element
    var statusMessage = document.querySelector('.status-message');

    // Show the status message if it's present and not empty
    if (statusMessage && statusMessage.textContent.trim() !== '') {
        statusMessage.style.display = 'block';
        setTimeout(function() {
            statusMessage.style.display = 'none';
        }, 3000); // 3000 milliseconds = 3 seconds
    }
});
