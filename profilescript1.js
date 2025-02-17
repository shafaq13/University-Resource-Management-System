// profilescript1.js

function applyFilter(filterType, studentID) {
    // Get the course name to filter by
    var information = document.getElementById('information').value;

    // Make an AJAX request to the PHP file that handles filtering
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Update the course list with the filtered data
            document.getElementById('courseList').innerHTML = this.responseText;
        }
    };

    // Define the URL of the PHP file handling filtering
    var url = 'filter.php?filterType=' + filterType + '&information=' + encodeURIComponent(information) + '&userID=' + studentID;
    xhr.open('GET', url, true);
    xhr.send();
}
