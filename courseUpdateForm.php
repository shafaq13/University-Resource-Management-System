<?php
include 'connection(urms).php'; 
$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$id = '';
$courseName = '';
$sem = '';

if (isset($_GET['ID'])) {
    $id = $mysqli->real_escape_string($_GET['ID']);
    $query = "SELECT * FROM Course WHERE Course_ID = '$id'";
    $result = $mysqli->query($query);

    if ($row = $result->fetch_assoc()) {
        $courseName = $row['Course_Name'];
        $sem = $row['Course_sem'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Table</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styletable.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <form action="updateHandlercourse.php" method="post">
        <input type="hidden" name="ID" value="<?php echo $id; ?>">
        <input type="text" name="Course_Name" value="<?php echo $courseName; ?>">
        <input type="number" name="Semester" value="<?php echo $sem; ?>">
       
       <input type="submit" value="Update">
    </form>
</body>
</html>