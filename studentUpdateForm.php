<?php
include 'connection(urms).php'; 
$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$id = '';
$studentName = '';
$pass = '';
$studentEmail = '';
$studentPhone = '';
$studentJoiningDate = '';

if (isset($_GET['ID'])) {
    $id = $mysqli->real_escape_string($_GET['ID']);
    $query = "SELECT * FROM Student WHERE ID = '$id'";
    $result = $mysqli->query($query);

    if ($row = $result->fetch_assoc()) {
        $studentName = $row['Student_Name'];
        $pass = $row['pass'];
        $studentEmail = $row['Student_email'];
        $studentPhone = $row['Student_phone'];
        $studentJoiningDate = $row['Student_joining_date'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Table</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styletable.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <form action="updateHandlerstudent.php" method="post">
        <input type="hidden" name="ID" value="<?php echo $id; ?>">
        <input type="text" name="Student_Name" value="<?php echo $studentName; ?>">
        <input type="text" name="pass" value="<?php echo $pass; ?>">
        <input type="text" name="Student_email" value="<?php echo $studentEmail; ?>">
        <input type="text" name="Student_phone" value="<?php echo $studentPhone; ?>">
        <input type="text" name="Student_joining_date" value="<?php echo $studentJoiningDate; ?>">
       
       <input type="submit" value="Update">
    </form>
</body>
</html>