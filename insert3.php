<?php
include 'connection(URMS).php';

// Function to update enrollments based on the current date
function updateEnrollments()
{
    global $conn;

    // Retrieve the list of students from the database
    $studentsQuery = "SELECT ID FROM Student";
    $studentsResult = mysqli_query($conn, $studentsQuery);

    if ($studentsResult === false) {
        die("Error fetching students: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($studentsResult)) {
        $studentID = $row['ID'];
        $currentSemester = getCurrentSemester($studentID);

        // Retrieve and display all the courses for the current semester for the student
        $coursesForStudent = getCoursesForStudent($studentID, $currentSemester);

        echo "StudentID: $studentID\n";
        echo "Courses for the current semester: " . implode(', ', $coursesForStudent) . "\n";

        foreach ($coursesForStudent as $courseID) {
            // Get the current teacher for the course
            $teacherID = getTeacherForCourse($courseID);

            echo "Updating Enrollment for StudentID: $studentID, CourseID: $courseID, TeacherID: $teacherID\n";

            // Check if the enrollment already exists
            if (isEnrollmentExists($studentID, $courseID)) {
                // Update the existing enrollment
                $updateStmt = mysqli_prepare($conn, "UPDATE Enrolled SET Teacher_ID = ? WHERE Student_ID = ? AND Course_ID = ?");
                mysqli_stmt_bind_param($updateStmt, 'sss', $teacherID, $studentID, $courseID);
                mysqli_stmt_execute($updateStmt);

                // Check for errors during execution
                if (mysqli_stmt_error($updateStmt)) {
                    echo "Error updating enrollment for Student $studentID, Course $courseID, Teacher $teacherID: " . mysqli_stmt_error($updateStmt) . "\n";
                } else {
                    echo "Enrollment updated successfully for Student $studentID, Course $courseID, Teacher $teacherID\n";
                }

                // Close the update statement
                mysqli_stmt_close($updateStmt);
            } else {
                // If enrollment doesn't exist, insert a new enrollment
                enrollStudent($studentID, $courseID, $teacherID);
            }
        }
    }
}

// Call the function to update enrollments
updateEnrollments();

function getCoursesForStudent($studentID, $semester)
{
    global $conn;

    $courses = [];

    // Assuming you have a way to query the courses for a specific semester and student
    $query = "SELECT Course_ID FROM Course WHERE Course_sem = ? ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $semester);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        // Handle query error
        return [];
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row['Course_ID'];
    }

    return $courses;
}

function enrollStudent($studentID, $courseID, $teacherID)
{
    global $conn;

    // Use prepared statement to insert a new enrollment
    $stmt = mysqli_prepare($conn, "INSERT INTO Enrolled (Student_ID, Course_ID, Teacher_ID) VALUES (?, ?, ?)");

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'sss', $studentID, $courseID, $teacherID);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Check for errors during execution
    if (mysqli_stmt_error($stmt)) {
        echo "Error inserting enrollment for Student $studentID, Course $courseID, Teacher $teacherID: " . mysqli_stmt_error($stmt) . "\n";
    } else {
        echo "Enrollment inserted successfully for Student $studentID, Course $courseID, Teacher $teacherID\n";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}


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

function getCoursesForSemester($semester)
{
    global $conn;

    $courses = [];

    // Assuming you have a way to query the courses for a specific semester
    $query = "SELECT Course_ID FROM Course WHERE Course_sem = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $semester);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        // Handle query error
        return [];
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row['Course_ID'];
    }

    return $courses;
}

function getTeacherForCourse($courseID)
{
    global $conn;

    // Assuming there's a mapping between courses and teachers in the database
    $query = "SELECT Teacher_ID FROM Recourse WHERE Course_ID = ? AND TypeId = 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $courseID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        // Handle query error
        return null;
    }

    $row = mysqli_fetch_assoc($result);

    // Return the Teacher_ID if a result is found, otherwise return null
    return $row ? $row['Teacher_ID'] : null;
}

function isEnrollmentExists($studentID, $courseID)
{
    global $conn;

    $query = "SELECT COUNT(*) as count FROM Enrolled WHERE Student_ID = ? AND Course_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $studentID, $courseID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return ($result && mysqli_fetch_assoc($result)['count'] > 0);
}

// Close the database connection
mysqli_close($conn);


?>