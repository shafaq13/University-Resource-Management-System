document.addEventListener('DOMContentLoaded', function () {
    // Get the button and additional details element
    var detailsButton = document.getElementById('detailsButton');
    var additionalDetails = document.getElementById('additionalDetails');

    // Check if the elements exist on the page
    if (detailsButton && additionalDetails) {
        // Add a click event listener to the button
        detailsButton.addEventListener('click', function () {
            // Toggle the visibility of additional details
            additionalDetails.classList.toggle('hidden');
        });
    }
});
