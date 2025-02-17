<?php
include 'connection(URMS).php';

$coursesTeachers = [
    "MT119" => "t10005",
    "NS1001" => "t10006",
    "CS118" => "t10018",
    "CS1004" => "t10008",
    "EE1005" => "t10009",
    "EE2003" => "t10010",
    "CS218" => "t10011",
    "MT104" => "t10012",
    "CS211" => "t10013",
    "CS2006" => "t10014",
    "CS325" => "t10015",
    "CS3005" => "t10017",
    "CS2005" => "t10000",
    "MT3001" => "t10002",
    "CS3004" => "t10019",
    "CS307" => "t10020",
    "CS461" => "t10021",
    "SS108" => "t10022",
    "CS3001" => "t10024",
    "CS4042" => "t10026",
    "CS4046" => "t10028",
    "SE4031" => "t10030",
];

// Dummy links for each type and course
$dummyLinks = [];
foreach ($coursesTeachers as $courseID => $teacherID) {
    $dummyLinks[$courseID] = [
        1 => "http://example.com/typeDetails.php?typeId=1&courseId=$courseID",
        2 => "http://example.com/typeDetails.php?typeId=2&courseId=$courseID",
        3 => "http://example.com/typeDetails.php?typeId=3&courseId=$courseID",
        4 => "http://example.com/typeDetails.php?typeId=4&courseId=$courseID",
        
    ];
}

foreach ($coursesTeachers as $courseID => $teacherID) {
    // Check if the course and teacher exist
    if (isCourseExists($courseID) && isTeacherExists($teacherID)) {
        // Insert data into Recourse table for each type and link
        foreach ($dummyLinks[$courseID] as $type => $dummyLink) {
            $sql = "INSERT INTO Recourse (TypeId, Course_ID, Teacher_ID, Link) VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssss', $type, $courseID, $teacherID, $dummyLink);

            if (mysqli_stmt_execute($stmt)) {
                echo "Recourse data inserted successfully for Type $type, Course $courseID, Teacher $teacherID\n";
            } else {
                echo "Error inserting data for Type $type, Course $courseID, Teacher $teacherID: " . mysqli_stmt_error($stmt) . "\n";
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Course or Teacher not found for Course $courseID, Teacher $teacherID\n";
    }
}

mysqli_close($conn);

// Function to check if a course exists
function isCourseExists($courseID)
{
    global $conn;

    $query = "SELECT COUNT(*) as count FROM Course WHERE Course_ID = '$courseID'";
    $result = mysqli_query($conn, $query);

    return ($result && mysqli_fetch_assoc($result)['count'] > 0);
}

// Function to check if a teacher exists
function isTeacherExists($teacherID)
{
    global $conn;

    $query = "SELECT COUNT(*) as count FROM Teacher WHERE ID = '$teacherID'";
    $result = mysqli_query($conn, $query);

    return ($result && mysqli_fetch_assoc($result)['count'] > 0);
}
?>