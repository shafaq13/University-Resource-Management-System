<?php
// filterCourses.php

// Include your database connection file
include 'connection(urms).php';

// Function to get the current semester for a student
function getCurrentSemester($studentID)
{
    global $conn;

    // Assuming you have a way to query the joining date of the student from the database
    $query = "SELECT Student_joining_date FROM Student WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $studentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        // Handle query error
        return 0; // Return 0 or handle the error in your way
    }

    $joiningDateRow = mysqli_fetch_assoc($result);
    $joiningDate = new DateTime($joiningDateRow['Student_joining_date']);

    // Get the current date
    $currentDate = new DateTime();

    // Calculate the current semester based on the provided conditions
    $currentSemester = ($currentDate->format('m') >= '01' && $currentDate->format('m') <= '07') ?
        (($currentDate->diff($joiningDate)->y) * 2) :
        (($currentDate->diff($joiningDate)->y) * 2) + 1;

    // Adjust the semester for the academic year transition for second-year students
    // You may need to customize this logic based on your database structure
    // Example: If the student joined in the year 2022 or earlier, no adjustment is needed
    if ($joiningDate->format('Y') < 2023) {
        return $currentSemester;
    }

    // Adjust for the academic year transition for second-year students
    if ($currentSemester > 2) {
        $currentSemester -= 2; // Subtract 2 for the transition to the second year
    }

    return $currentSemester;
}

// Get filter parameters from the AJAX request
$filterType = $_GET['filterType'];
$Searchinfo = $_GET['information'];
$studentID = $_GET['userID']; // Corrected variable name

// Get the current semester for the student
$currentSemester = getCurrentSemester($studentID);

// Prepare SQL statement based on the selected filter type
switch ($filterType) {
    case 'ByCourseName':
        $stmtFilter = $conn->prepare("SELECT DISTINCT c.*, r.Link, R.TypeId, RT.TypeName FROM Course c
                                        JOIN Recourse r ON c.Course_ID = r.Course_ID
                                        JOIN RecourseType RT on R.TypeId=RT.TypeId
                                        WHERE c.Course_Name LIKE ? AND c.Course_sem <= ?");
        $stmtFilter->bind_param("ss", $Searchinfo, $currentSemester);
        break;
    case 'ByTeacherName':
        $stmtFilter = $conn->prepare("SELECT DISTINCT C.*, R.Link, R.TypeId, RT.TypeName FROM Teacher T
                                        JOIN Recourse R ON T.ID = R.Teacher_ID
                                        JOIN Course C ON R.Course_ID = C.Course_ID
                                        JOIN RecourseType RT on R.TypeId=RT.TypeId
                                        WHERE T.Teacher_Name = ? AND C.Course_sem <= ?");
        $stmtFilter->bind_param("ss", $Searchinfo,$currentSemester);
        break;
    case 'BySemester':
            // Check if $Searchinfo is a valid integer
            if (!is_numeric($Searchinfo) || intval($Searchinfo) != $Searchinfo) {
                echo "Invalid semester value. Please provide a valid integer.";
                exit;
            }
        
            // Create a view table for the selected semester
            $viewTable = "View_Semester_" . $Searchinfo;
        
            // Check if the view table already exists, if not, create it
            $createViewQuery = "CREATE VIEW IF NOT EXISTS $viewTable AS
                                SELECT DISTINCT C.*, R.Link, R.TypeId, RT.TypeName
                                FROM Course C
                                JOIN Recourse R ON C.Course_ID = R.Course_ID 
                                JOIN RecourseType RT on R.TypeId=RT.TypeId
                                WHERE C.Course_sem = $Searchinfo AND C.Course_sem <= $currentSemester";
            
            // Execute the create view query
            if (!$conn->query($createViewQuery)) {
                // Handle the error if view creation fails
                echo "Error creating view: " . $conn->error;
                exit;
            }
        
            // Use the view table in the main filter query
            $stmtFilter = $conn->prepare("SELECT * FROM $viewTable");
            break;
        
        case 'ByYear':
                // Check if $Searchinfo is a valid integer
                if (!is_numeric($Searchinfo) || intval($Searchinfo) != $Searchinfo || !in_array($Searchinfo, [1, 2, 3, 4])) {
                    echo "Invalid year value. Please provide a valid integer (1, 2, 3, or 4).";
                    exit;
                }                
            
                // Create a view table for the selected year
                $viewTable = "View_Year_" . $Searchinfo;
            
                // Check if the view table already exists, if not, create it
                $createViewQuery = "CREATE VIEW IF NOT EXISTS $viewTable AS
                    SELECT DISTINCT C.*, R.Link, R.TypeId, RT.TypeName
                    FROM Course C
                    JOIN Recourse R ON C.Course_ID = R.Course_ID
                    JOIN RecourseType RT on R.TypeId=RT.TypeId
                    WHERE ((C.Course_sem IN (1, 2) AND $Searchinfo = 1)
                    OR (C.Course_sem IN (3, 4) AND $Searchinfo = 2)
                    OR (C.Course_sem IN (5, 6) AND $Searchinfo = 3)
                    OR (C.Course_sem IN (7, 8) AND $Searchinfo = 4))
                    AND C.Course_sem <= " . intval($currentSemester);
            
                // Execute the create view query
                if (!$conn->query($createViewQuery)) {
                    // Handle the error if view creation fails
                    echo "Error creating view: " . $conn->error;
                    exit;
                }
            
                // Use the view table in the main filter query
                $stmtFilter = $conn->prepare("SELECT * FROM $viewTable");
                break;
            case 'ViewAll':
                // Create a view table for all courses
                $viewTable = "View_AllCourses";

                // Check if the view table already exists, if not, create it
                $createViewQuery = "CREATE VIEW IF NOT EXISTS $viewTable AS
                                    SELECT DISTINCT C.*, R.Link, R.TypeId, RT.TypeName
                                    FROM Course C
                                    JOIN Recourse R ON C.Course_ID = R.Course_ID
                                    JOIN RecourseType RT on R.TypeId=RT.TypeId
                                    WHERE C.Course_sem <= $currentSemester";

                // Execute the create view query
                if (!$conn->query($createViewQuery)) {
                    // Handle the error if view creation fails
                    echo "Error creating view: " . $conn->error;
                    exit;
                }

                // Use the view table in the main filter query
                $stmtFilter = $conn->prepare("SELECT * FROM $viewTable");
                break;
    default:
        // Handle unknown filter type
        echo "Invalid filter type";
        exit;
}

// Execute the prepared statement
if ($stmtFilter->execute()) {
    $resultFilter = $stmtFilter->get_result();

    // Display filtered courses
    if ($resultFilter->num_rows > 0) {
        echo '<style>
                table {
                    font-family: Arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #333;
                    text-align: left;
                    padding: 8px;
                    border-bottom: 2px solid #333; /* Dark bottom border for separation */
                }
                th {
                    background-color: #444;
                    color: white; 
                }
            </style>';

        echo '<table>';
        echo '<tr>
            <th>Type ID</th>
            <th>Type Name</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Link</th>
            </tr>';
        $displayedCourses = array();

        while ($filteredCourse = $resultFilter->fetch_assoc()) {
            if (!in_array($filteredCourse['Course_ID'], $displayedCourses)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($filteredCourse['TypeId']) . '</td>';
                echo '<td>' . htmlspecialchars($filteredCourse['TypeName']) . '</td>';
                echo '<td>' . htmlspecialchars($filteredCourse['Course_ID']) . '</td>';
                echo '<td>' . htmlspecialchars($filteredCourse['Course_Name']) . '</td>';
                echo '<td><a href="typeDetails.php?typeId=' . htmlspecialchars($filteredCourse['TypeId']) . '&courseId=' . htmlspecialchars($filteredCourse['Course_ID']) . '" target="_blank">View Resources</a></td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    } else {
        echo '<p>No courses found based on the selected filter. ' . '</p>';
    }



} else {
    // Handle execution error
    die('Error: ' . $stmtFilter->error);
}

// Close the database connection
$stmtFilter->close();


// Drop the view table if it exists
if (isset($viewTable)) {
    $dropViewQuery = "DROP VIEW IF EXISTS $viewTable";
    if (!$conn->query($dropViewQuery)) {
        // Handle the error if dropping the view fails
        echo "Error dropping view: " . $conn->error;
    }
}

$conn->close();
?>


