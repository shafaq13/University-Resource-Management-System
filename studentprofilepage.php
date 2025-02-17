<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <script src="profilescript1.js"></script>
    <link rel="stylesheet" href="profilescript.css">
    <style>
        /* Updated styles for a stylish and longer search box */
        .search-container {
            margin-top: 20px;
            text-align: center;
        }

        label {
            font-size: 18px;
        }

        .fancy-search-box {
            display: flex;
            max-width: 1000px; /* Adjust the width as needed */
            margin: 10px auto;
        }

        .fancy-search-box input {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            border: 2px solid #3498db;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .fancy-search-box .fancy-search-button {
            padding: 15px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: #fff;
            border: 2px solid #3498db;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .fancy-search-box .fancy-search-button:hover {
            background-color: #2980b9;
        }

        /* Style for the dynamic course list */
        #courseList {
            margin-top: 20px;
        }

        #information {
            width: 600px; /* Adjust the width as needed */
            padding: 15px;
            font-size: 16px;
            border: 2px solid #3498db;
            border-radius: 5px 0 0 5px;
            outline: none;
            margin-right: 10px; /* Add some space between the input and buttons */
            margin-bottom: 10px; /* Add space between the input and the filter buttons */
        }

        /* Updated styles for the search filter */
        .search-filter {
            margin-top: 20px;
            text-align: center;
            display: flex;
            align-items: center; /* Vertically align the items in the center */
            justify-content: space-around; /* Adjust as needed */
            flex-wrap: wrap; /* Wrap to the next line if the buttons don't fit */
        }

        .filter-button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #3498db;
            color: #fff;
            border: 2px solid #3498db;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 10px; /* Add space between the buttons */
            margin-left: 5px; /* Adjust the spacing between buttons */
        }

        .filter-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <header>
        <h1>Student Profile</h1>
    </header>

    <main>
        <?php
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

        // Retrieve student ID from the URL parameter
        $studentID = $_GET['userID'];

        // Include your database connection file
        include 'connection(urms).php';

        // Call the getCurrentSemester function
        $currentSemester = getCurrentSemester($studentID);

        // Prepare SQL statement to retrieve student information based on ID
        $stmtStudent = $conn->prepare("SELECT * FROM Student WHERE ID = ?");
        $stmtStudent->bind_param("s", $studentID);
        $stmtStudent->execute();
        $resultStudent = $stmtStudent->get_result();

        // Check if a student with the given ID exists
        if ($resultStudent->num_rows > 0) {
            $student = $resultStudent->fetch_assoc();

            // Display student information
            echo '<section class="student-info">';
            echo '<h2>' . $student['Student_Name'] . '</h2>';
            echo '<p><strong>ID:</strong> ' . $student['ID'] . '</p>';
            echo '<p><strong>Email:</strong> ' . $student['Student_email'] . '</p>';
            echo '<p><strong>Current Semester:</strong> ' . $currentSemester . '</p>'; // Display current semester
            echo '<p><strong>Phone Number:</strong> ' . $student['Student_phone'] . '</p>';
            echo '<p><strong>Joining Date:</strong> ' . $student['Student_joining_date'] . '</p>';

            // Fetch enrolled courses for the student with both Course ID and Course Name
            $stmtEnrolled = $conn->prepare("SELECT E.Course_ID, C.Course_Name FROM Enrolled E
                                            JOIN Course C ON E.Course_ID = C.Course_ID
                                            WHERE E.Student_ID = ?");
            $stmtEnrolled->bind_param("s", $studentID);
            $stmtEnrolled->execute();
            $resultEnrolled = $stmtEnrolled->get_result();

            // Display enrolled courses
            if ($resultEnrolled->num_rows > 0) {
                echo '<p><strong>Enrolled Courses:</strong></p>';
                echo '<ul>';
                while ($enrolledCourse = $resultEnrolled->fetch_assoc()) {
                    echo '<li>' . $enrolledCourse['Course_ID'] . ' - ' . $enrolledCourse['Course_Name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p><strong>No enrolled courses found.</strong></p>';
            }

            echo '</section>';
        } else {
            // Handle the case when no student is found
            echo '<p>No student found with the given ID.</p>';
        }

        // Close the database connections
        $stmtStudent->close();
        $stmtEnrolled->close();
        $conn->close();
        ?>
        <!-- Search filter section -->
        <section class="search-filter">
            <!-- Course name input field -->
            <input type="text" id="information" placeholder="Enter information">
            <!-- Search buttons -->
            <button class="filter-button" onclick="applyFilter('ViewAll', '<?php echo $studentID; ?>')">View All</button>
            <button class="filter-button" onclick="applyFilter('ByCourseName', '<?php echo $studentID; ?>')">Search By Course Name</button>
            <button class="filter-button" onclick="applyFilter('ByTeacherName', '<?php echo $studentID; ?>')">Search By Teacher Name</button>
            <button class="filter-button" onclick="applyFilter('BySemester', '<?php echo $studentID; ?>')">Search By Semester</button>
            <button class="filter-button" onclick="applyFilter('ByYear', '<?php echo $studentID; ?>')">Search By Year</button>
        </section>

        <!-- Dynamic course list section -->
        <div id="courseList"></div>
    </main>

</body>

</html>
