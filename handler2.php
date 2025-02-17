<?php
include 'connection(urms).php';

// Suppress warnings
error_reporting(E_ALL & ~E_WARNING);

$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assume an error state to begin with
    $success = false;
    $error = '';

    // Check if ID is set in POST request
    if (!isset($_POST['ID'])) {
        $error = 'No ID provided';
    } else {
        $id = $mysqli->real_escape_string($_POST['ID']);
        
        // Determine which table to update based on a hidden input or query parameter
        $table = '';
        $fields = [];
        if (isset($_POST['table'])) {
            switch ($_POST['table']) {
                case 'SystemAdmin':
                    $table = 'SystemAdmin';
                    $fields = ['Admin_Name', 'pass'];
                    break;
                case 'Student':
                    $table = 'Student';
                    $fields = ['Student_Name', 'pass', 'Student_email', 'Student_phone', 'Student_joining_date'];
                    break;
                case 'Teacher':
                    $table = 'Teacher';
                    $fields = ['Teacher_Name', 'pass', 'Teacher_email', 'Teacher_phone'];
                    break;
                default:
                    $error = 'Unknown table';
                    break;
            }
        } else {
            $error = 'Table not specified';
        }

        if ($table !== '') {
            // Build the SQL query dynamically based on the table and fields
            $query = "UPDATE $table SET ";
            $updateParts = [];
            $params = [];
            $types = '';

            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $updateParts[] = "$field = ?";
                    $params[] = $_POST[$field];
                    $types .= 's';
                }
            }

            if (!empty($updateParts)) {
                $query .= implode(', ', $updateParts) . " WHERE ID = ?";
                $params[] = $id;
                $types .= 's';

                // Prepare, bind, and execute the statement
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param($types, ...$params);
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error = $stmt->error;
                }
                $stmt->close();
            } else {
                $error = 'No fields provided for update';
            }
        }
    }

    if ($success) {
        echo $redirectUrl;
        // If the update was successful, redirect to the specific table page
        switch ($table) {
            case 'SystemAdmin':
                $redirectUrl = 'systemadmintablepage.php';
                break;
            case 'Teacher':
                $redirectUrl = 'teachertablepage.php';
                break;
            case 'Student':
                $redirectUrl = 'studenttablepage.php';
                break;
            
            default:
                $redirectUrl = 'index.php'; // default page or error page if the table is unknown
                break;
        }
        
        //header("Location: $redirectUrl");
        exit();
    } else {
        // If there was an error, set the header and return the error in JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $error]);
        exit();
    }
    
}

$mysqli->close();
?>
