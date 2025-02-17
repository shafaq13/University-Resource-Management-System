<?php
include 'connection(urms).php';
try {
    // Your database connection code...
    $conn = new mysqli($servername, $username, $password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $recourseID = $_POST['recourseID'];
    $courseID = $_POST['courseID'];
    $resourceLink = $_POST['resourceLink'];

    $stmt = $conn->prepare("UPDATE recourse SET Link = ? WHERE TypeId = ?");
    $stmt->bind_param("ss", $resourceLink, $recourseID);

    if ($stmt->execute()) {
        echo "Resource updated successfully.";
        header("Location: teacherprofilepage.php");
            exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
